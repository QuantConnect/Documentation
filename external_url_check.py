# QUANTCONNECT.COM - Democratizing Finance, Empowering Individuals.
# Lean Algorithmic Trading Engine v2.0. Copyright 2014 QuantConnect Corporation.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

# Broken-link checker for the QuantConnect Lean.Brokerages.* and Lean.DataSource.*
# repositories.
#
# Unlike url_check.py (which validates this documentation repo's own .html/.php
# pages and their on-disk structure), this script scans one or more *external*
# repositories that have already been cloned to disk. It extracts links from
# their Markdown files and validates them:
#   - Markdown links:  [text](url)
#   - Bare URLs:       https://example.com/...
#   - HTML anchors:    <a href="url">
#
# Each link is checked for:
#   - HTTP errors: GET each URL, flag 4xx responses and soft-404 pages. A 401/403
#     is re-checked with a browser-impersonating client (curl_cffi) first, since
#     many sites bot-block plain HTTP clients on otherwise-valid links. If that
#     re-check reveals a real 404, it's escalated to a failing error (so a dead
#     link can't hide behind a 403); if it still can't verify, it stays a warning.
#   - Missing sections: a #fragment into the QuantConnect docs is validated against
#     the docs pages on disk (this script runs inside the Documentation repo),
#     because the HTTP check can't see the fragment - the server returns 200
#     whether or not the section exists.
#   - Deprecated paths: docs links using the old "our-platform" path (which still
#     redirects) so they can be updated to "cloud-platform"/"local-platform".
#
# Each error category has a severity ("error" or "warning") in the SEVERITY dict.
# Only "error" categories cause a non-zero exit code. There is no GitHub issue
# management - a non-zero exit (a failed CI run) is the signal that links need
# fixing.
#
# Usage:
#   pip install aiohttp
#   python external_url_check.py <repo_dir> [<repo_dir> ...]
#   python external_url_check.py --report broken-links-report.md <repo_dir> ...
#
# Each <repo_dir> is the root of a cloned repository. The directory name is used
# as the repo label in the output.

import argparse
import asyncio
import datetime
import re
import sys
import time
from collections import defaultdict, namedtuple
from pathlib import Path

import aiohttp
from browser_recheck import browser_recheck
from doc_anchors import build_file_index, check_deprecated_path, check_section_anchor

# -- Constants ----------------------------------------------------------------
CONCURRENCY = 50          # simultaneous HTTP requests
USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36"

# File extensions whose links we scan. Markdown is where the docs/prose live.
SCAN_EXTENSIONS = {".md"}

# The Documentation repo root, used to validate #section anchors on docs links.
DOCS_BASE = Path(__file__).resolve().parent

# "our-platform" is an alias that redirects to "cloud-platform"/"local-platform".
# Accept it for anchor validation (so a valid section isn't mis-flagged), while a
# separate deprecated_path check still reports the link so it can be updated.
PATH_ALIASES = (
    ("our platform", "cloud platform"),
    ("our platform", "local platform"),
)

# -- Error categories ---------------------------------------------------------
# Each category can be "error" (fails the check) or "warning" (reported, passes).
# 4xx auth/forbidden codes are warnings because many third-party sites (exchanges,
# data vendors) return them to bots rather than to a real broken link.
SEVERITY = {
    "404":             "error",
    "soft_404":        "error",
    "missing_section": "error",
    "deprecated_path": "error",
    "400":             "warning",
    "401":             "warning",
    "403":             "warning",
    "failed_request":  "warning",
}

# Human-readable category labels for the Markdown report.
REPORT_LABELS = {
    "404":             "404 Not Found",
    "soft_404":        "Soft 404",
    "missing_section": "Missing Section",
    "deprecated_path": "Deprecated Path",
}

# A single finding. files are repo-relative path strings; repo is the repo name.
Finding = namedtuple("Finding", "category reason url files repo")


def _finding(category: str, reason: str, url: str,
             files: list[Path], repo_dir: Path) -> Finding:
    return Finding(category, reason, url,
                   [str(f.relative_to(repo_dir)) for f in files], repo_dir.name)


# -- Link extraction ----------------------------------------------------------

# Markdown inline link: [label](destination). Destination may carry a title:
# [x](url "title"). Reference-style and image links share the (dest) shape.
_MD_LINK = re.compile(r"\]\(\s*<?([^)>\s]+)>?")
# HTML anchor href.
_HREF = re.compile(r"""href\s*=\s*["']([^"']+)["']""")
# Bare URL not already captured above.
_BARE_URL = re.compile(r"""(?<![("'=])\bhttps?://[^\s)>\]"'`]+""")
# Fenced (```...``` / ~~~...~~~) and inline (`...`) code spans. These hold code,
# not links - e.g. Python `history[Type](symbol, ...)` is not a Markdown link.
_CODE_SPAN = re.compile(r"```.*?```|~~~.*?~~~|`[^`]*`", re.DOTALL)


def _clean_url(url: str) -> str:
    """Strip trailing punctuation that commonly clings to a URL in prose."""
    return url.strip().rstrip(".,;:!?*'\"")


def extract_links(text: str) -> set[str]:
    """Extract every link (Markdown, HTML href, and bare URL) from a blob of text."""
    text = _CODE_SPAN.sub(" ", text)   # ignore links that live inside code
    links: set[str] = set()
    for pattern in (_MD_LINK, _HREF):
        for match in pattern.finditer(text):
            links.add(_clean_url(match.group(1)))
    for match in _BARE_URL.finditer(text):
        links.add(_clean_url(match.group(0)))
    # Drop templated/placeholder links such as {{ url }}, <username>, $VAR.
    return {u for u in links if u and not any(c in u for c in "{}$<>")}


def collect_links(repo_dir: Path) -> dict[str, list[Path]]:
    """Walk a repo and return {link: [files it appears in]} for scannable files."""
    link_files: dict[str, list[Path]] = {}
    for path in sorted(repo_dir.rglob("*")):
        if path.suffix.lower() not in SCAN_EXTENSIONS or not path.is_file():
            continue
        if ".git" in path.parts:
            continue
        try:
            text = path.read_text(encoding="utf-8", errors="replace")
        except Exception:
            continue
        for link in extract_links(text):
            link_files.setdefault(link, []).append(path)
    return link_files


# -- HTTP checking ------------------------------------------------------------

def _is_external(url: str) -> bool:
    return url.startswith("http://") or url.startswith("https://")


async def check_url(session: aiohttp.ClientSession, semaphore: asyncio.Semaphore,
                    url: str, files: list[Path], repo_dir: Path,
                    findings: list[Finding], broken_pages: set[str]):
    """Check a single external URL for errors. Records page-level breakage (404 /
    soft-404) in broken_pages so a redundant on-disk finding isn't also reported.

    A 401/403 is re-checked with a browser-impersonating client before warning, so
    bot-blocked-but-valid links don't generate noise."""
    async with semaphore:
        try:
            async with session.get(
                url, allow_redirects=True,
                timeout=aiohttp.ClientTimeout(total=60),
            ) as resp:
                match resp.status:
                    case 400:
                        findings.append(_finding("400", "400 Bad Request", url, files, repo_dir))
                    case 401 | 403:
                        # Likely bot/WAF blocking - re-check with a browser-like client.
                        verdict = await browser_recheck(url)
                        if verdict == "broken":
                            # The block was hiding a genuinely dead link - fail on it.
                            findings.append(_finding(
                                "404", "404 Not found (confirmed via browser re-check)",
                                url, files, repo_dir))
                            broken_pages.add(url)
                        elif verdict == "blocked":
                            cat = str(resp.status)
                            msg = f"{resp.status} {'Unauthorized' if resp.status == 401 else 'Forbidden'}"
                            findings.append(_finding(cat, msg, url, files, repo_dir))
                        # verdict == "ok" -> link is valid, no finding
                    case 404:
                        findings.append(_finding("404", "404 Not found", url, files, repo_dir))
                        broken_pages.add(url)
                    case 200:
                        # Soft 404: site redirected us to its /404 page.
                        if str(resp.url).rstrip("/").endswith("/404"):
                            findings.append(_finding("soft_404", "Soft 404 (page not found)",
                                                     url, files, repo_dir))
                            broken_pages.add(url)
        except Exception:
            findings.append(_finding("failed_request", "Failed to request", url, files, repo_dir))


# -- Per-repo driver ----------------------------------------------------------

async def check_repo(session: aiohttp.ClientSession, semaphore: asyncio.Semaphore,
                     repo_dir: Path, findings: list[Finding],
                     file_index: dict[str, list[str]],
                     deferred: list[Finding], broken_pages: set[str]) -> int:
    """Scan one repo. Returns the number of external links checked over HTTP."""
    link_files = collect_links(repo_dir)

    tasks = []
    for url, files in link_files.items():
        if not _is_external(url):
            continue   # anchors / mailto: are left alone

        # On-disk checks (no HTTP). Deferred until HTTP is done so we can drop
        # them when the page itself turns out to be 404/soft-404.
        reason = check_section_anchor(url, DOCS_BASE, file_index, path_aliases=PATH_ALIASES)
        if reason:
            deferred.append(_finding("missing_section", reason, url, files, repo_dir))
        deprecated = check_deprecated_path(url)
        if deprecated:
            deferred.append(_finding("deprecated_path", deprecated, url, files, repo_dir))

        tasks.append(check_url(session, semaphore, url, files, repo_dir, findings, broken_pages))

    for coro in asyncio.as_completed(tasks):
        await coro
    return len(tasks)


# -- Reporting ----------------------------------------------------------------

def _fmt_console(f: Finding) -> str:
    # Prefix each file with the repo name so findings stay attributable.
    locs = "\n\t\t".join(f"{f.repo}/{p}" for p in f.files)
    return f"{f.reason}:\n\t{f.url}\n\t[\n\t\t{locs}\n\t]"


def write_markdown_report(path: Path, findings: list[Finding], repo_count: int):
    """Write a per-repo Markdown report of the error-severity findings."""
    errors = [f for f in findings if SEVERITY[f.category] == "error"]
    by_repo: dict[str, list[Finding]] = defaultdict(list)
    for f in errors:
        by_repo[f.repo].append(f)

    today = datetime.date.today().isoformat()
    lines = [
        "# Broken Links — Lean.Brokerages.* and Lean.DataSource.* Repos",
        "",
        f"_Generated by `external_url_check.py` on {today}. "
        f"Scanned {repo_count} cloned repo(s)._",
        "",
        f"**{len(errors)} broken link(s)** across **{len(by_repo)} repo(s)**. Broken means an "
        "HTTP 404, a soft 404 (the page redirects to `/404`), a **missing section** (a "
        "`#anchor` into the QuantConnect docs pointing to a section that doesn't exist), or a "
        "**deprecated path** (a docs link still using the old `our-platform` path). Third-party "
        "links that merely block bots (403/timeout) are warnings and excluded here. Only repos "
        "with broken links are listed.",
        "",
        "---",
        "",
    ]
    for repo in sorted(by_repo):
        lines.append(f"## {repo}")
        lines.append("")
        lines.append(f"<https://github.com/QuantConnect/{repo}>")
        lines.append("")
        for f in sorted(by_repo[repo], key=lambda x: (x.category, x.url)):
            loc = ", ".join(f"`{p}`" for p in f.files)
            lines.append(f"- **{REPORT_LABELS[f.category]}** — {f.url} — in {loc}")
        lines.append("")

    path.write_text("\n".join(lines), encoding="utf-8")
    print(f"\nWrote report to {path} ({len(errors)} link(s), {len(by_repo)} repo(s)).")


# -- Main ---------------------------------------------------------------------

async def main():
    parser = argparse.ArgumentParser(
        description="Check Lean.Brokerages.*/Lean.DataSource.* repos for broken links.")
    parser.add_argument("repos", nargs="+", help="Paths to cloned repository roots.")
    parser.add_argument("--report", metavar="PATH",
                        help="Also write a per-repo Markdown report to PATH.")
    args = parser.parse_args()

    repo_dirs = [Path(p).resolve() for p in args.repos]
    for d in repo_dirs:
        if not d.is_dir():
            print(f"ERROR: not a directory: {d}")
            sys.exit(2)

    start = time.perf_counter()
    semaphore = asyncio.Semaphore(CONCURRENCY)
    connector = aiohttp.TCPConnector(limit=CONCURRENCY, limit_per_host=20)

    print(f"Indexing docs pages under {DOCS_BASE.name} for anchor checks ...", flush=True)
    file_index = build_file_index(DOCS_BASE)

    total_links = 0
    findings: list[Finding] = []
    deferred: list[Finding] = []
    broken_pages: set[str] = set()
    async with aiohttp.ClientSession(
        connector=connector, headers={"User-Agent": USER_AGENT},
    ) as session:
        for repo_dir in repo_dirs:
            print(f"Scanning {repo_dir.name} ...", flush=True)
            total_links += await check_repo(session, semaphore, repo_dir, findings,
                                            file_index, deferred, broken_pages)

    # Keep on-disk findings only for pages that actually resolve - if the page is
    # itself 404/soft-404 that's already reported, so don't double-count it.
    findings.extend(f for f in deferred if f.url not in broken_pages)

    print(f"\nChecked {total_links} unique links across {len(repo_dirs)} repo(s) "
          f"in {time.perf_counter() - start:.2f}s")

    # Report to console, grouped by category in severity order.
    by_cat: dict[str, list[Finding]] = defaultdict(list)
    for f in findings:
        by_cat[f.category].append(f)

    error_count = warning_count = 0
    for category in SEVERITY:
        items = by_cat.get(category, [])
        if not items:
            continue
        is_error = SEVERITY[category] == "error"
        label = "ERROR" if is_error else "WARNING"
        if is_error:
            error_count += len(items)
        else:
            warning_count += len(items)
        print(f"\n{'=' * 60}\n{label}S - {category} ({len(items)}):\n{'=' * 60}")
        for f in items:
            print(f"  {label}: {_fmt_console(f)}")

    print(f"\n{'-' * 60}")
    print(f"Summary: {error_count} error(s), {warning_count} warning(s)")
    print(f"{'-' * 60}")

    if args.report:
        write_markdown_report(Path(args.report), findings, len(repo_dirs))

    if error_count > 0:
        print(f"\nFAILED: {error_count} broken link(s) found.")
        sys.exit(1)
    if warning_count > 0:
        print(f"\nPASSED with {warning_count} warning(s).")
    else:
        print("\nAll links are valid!")


if __name__ == "__main__":
    asyncio.run(main())
