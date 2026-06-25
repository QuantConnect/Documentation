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

# URL checker for the QuantConnect documentation repository.
#
# Scans all .html/.php doc files and documentation-map.json for <a href="..."> links,
# then validates them:
#   - HTTP checks: GET each external URL, flag 4xx responses and soft-404 pages.
#   - Section anchors: verify that #fragment links map to real files on disk.
#   - Resource includes: verify <? include(DOCS_RESOURCES."...") targets exist.
#   - GitHub issues: confirm referenced Lean issues are still open.
#   - lean.io pages: confirm docs pages have content files in the GitHub tree.
#
# Each error category has a configurable severity ("error" or "warning") in the
# SEVERITY dict. Only "error" categories cause a non-zero exit code.
#
# When errors are found, the script creates or updates a GitHub issue (via `gh` CLI)
# titled "Fix broken documentation links". When all errors are resolved, it closes
# the issue automatically.
#
# Usage:
#   pip install aiohttp
#   python url_check.py                  # local run, no GitHub issue management
#   python url_check.py --create-issue   # CI run, creates/updates/closes GitHub issues

import argparse
import asyncio
import json
import os
import re
import subprocess
import sys
import time
from pathlib import Path

import aiohttp

from doc_anchors import build_file_index, check_section_anchor

# -- Constants ----------------------------------------------------------------
BASE_PATH = Path(__file__).resolve().parent                  # repo root
ROOT = "https://www.quantconnect.com/"
LEAN_IO = "https://www.lean.io/"
STRATEGY_PHP = BASE_PATH / "03 Writing Algorithms" / "42 Strategy Library" / "02 Tutorials.php"
LEAN_IO_FOLDERS = {"05 Lean CLI", "06 LEAN Engine"}
IGNORE_FILES = {str(BASE_PATH / "Resources" / "indicators" / "using-indicator.php")}

LEAN_IO_ERROR_URLS = [
    "/docs/v2/cloud-platform", "/docs/v2/local-platform",
    "/docs/v2/writing-algorithm", "/docs/v2/research-environment",
]
CONCURRENCY = 50          # simultaneous HTTP requests
USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36"

# -- Error categories ---------------------------------------------------------
# Each category can be "error" (fails the check) or "warning" (reported but passes).
SEVERITY = {
    "404":                  "error",
    "soft_404":             "error",
    "missing_section":      "error",
    "missing_resource":     "error",
    "deprecated_docs":      "error",
    "leanio_nonexistence":  "error",
    "github_issue_closed":  "error",
    "invalid_docs_page":    "error",
    "400":                  "warning",
    "401":                  "warning",
    "403":                  "warning",
    "failed_request":       "warning",
}

# -- File helpers -------------------------------------------------------------

def _path_to_link(filepath: str, skip_last: int = 0) -> str:
    """Convert a repo file path to the corresponding docs URL."""
    rel = os.path.relpath(filepath, BASE_PATH)
    parts = rel.replace("\\", "/").split("/")
    if skip_last:
        parts = parts[:-skip_last]
    # strip leading number prefix ("01 Foo" → "Foo")
    stripped = [p[p.index(" ") + 1:].strip() if " " in p and p.split(" ")[0].isdigit() else p
                for p in parts]
    slug = "/".join(stripped).lower().replace(" ", "-")
    return f"{ROOT}docs/v2/{slug}"


# -- Walk the tree once --------------------------------------------------------

def _collect_files() -> tuple[list[str], dict[str, list[str]]]:
    """Build the docs file index, then derive the doc files to scan from it.
    - file_index: all content files indexed by lowercased stem (anchor lookup)
    - doc_files: the .html/.php subset, for URL/resource extraction
    """
    file_index = build_file_index(BASE_PATH)
    doc_files = sorted(
        fp
        for files in file_index.values()
        for fp in files
        if (fp.endswith(".html") or fp.endswith(".php")) and fp not in IGNORE_FILES
    )
    return doc_files, file_index


# -- URL extraction -----------------------------------------------------------

def _get_all_urls(doc_files: list[str]) -> dict[str, list[str]]:
    """Extract all href URLs from doc files + documentation-map.json."""
    url_files: dict[str, list[str]] = {}
    modified_files: dict[str, list[str]] = {}   # filepath → new lines (only if changed)

    # 1) documentation-map.json
    map_path = BASE_PATH / "documentation-map.json"
    with open(map_path, encoding="utf-8") as f:
        doc_map = json.load(f)
    map_label = str(map_path)
    for value in doc_map.values():
        url, _, _, _ = _url_conversion(value, map_label, "")
        url_files.setdefault(url, []).append(map_label)

    # 2) All doc files
    for filepath in doc_files:
        try:
            with open(filepath, encoding="utf-8", errors="replace") as f:
                lines = f.readlines()
        except Exception:
            continue

        has_relative_link = False
        new_lines = list(lines)

        for i, line in enumerate(lines):
            href_idx = line.find("href")
            if href_idx < 0:
                continue
            a_idx = line[:href_idx].find("<a")
            if a_idx < 0:
                continue
            if "<?=" in line[href_idx:]:
                continue
            if "<!--" in line[:a_idx]:
                continue

            # Extract href values
            reconstructed = line[:a_idx + 3] + line[href_idx:]
            segments = reconstructed.replace("'", '"').split('a href="')[1:]
            for seg in segments:
                raw_url = seg.split('"')[0]

                if "{" in raw_url or "}" in raw_url or "$" in raw_url:
                    continue

                if not raw_url or raw_url.isspace():
                    url_files.setdefault("", []).append(filepath)
                    continue

                url, lean_io_url, is_relative, converted_line = _url_conversion(
                    raw_url, filepath, new_lines[i]
                )
                if is_relative:
                    new_lines[i] = converted_line
                    has_relative_link = True

                url_files.setdefault(url, []).append(filepath)

                if lean_io_url:
                    url_files.setdefault(lean_io_url, []).append(filepath)

        if has_relative_link:
            modified_files[filepath] = new_lines

    # Write back files with converted relative links
    for filepath, new_lines in modified_files.items():
        with open(filepath, "w", encoding="utf-8") as f:
            f.writelines(new_lines)

    return url_files


def _url_conversion(url: str, filepath: str, line: str) -> tuple[str, str, bool, str]:
    """Convert a raw href to an absolute URL.
    Returns (url, lean_io_url, has_relative_link, converted_line).
    """
    lean_io_url = ""
    has_relative_link = False
    converted_line = line

    if re.search(r"github\.com/QuantConnect/Lean/issues/\d+", url):
        url = url.replace("github.com", "api.github.com/repos").replace("www", "")

    if "http" not in url:
        if url.startswith("#"):
            old_url = url
            url = _path_to_link(filepath, 1) + url
            converted_line = line.replace(old_url, url.replace(ROOT, "/"))
            has_relative_link = True
        elif "mailto:" in url:
            pass
        elif not url.startswith("/"):
            url = _path_to_link(filepath, 2) + f"/{url}"
        else:
            url = f"{ROOT}{url.lstrip('/')}"

        if any(folder in filepath for folder in LEAN_IO_FOLDERS):
            lean_io_url = url.replace(ROOT, LEAN_IO)

    return url, lean_io_url, has_relative_link, converted_line


def _get_strategy_php_urls() -> dict[str, list[str]]:
    """Parse strategy URLs from the PHP strategy map."""
    if not STRATEGY_PHP.exists():
        return {}

    text = STRATEGY_PHP.read_text(encoding="utf-8")
    urls: list[str] = []

    # Extract link values from 'link' => '...'
    for m in re.finditer(r"'link'\s*=>\s*'([^']+)'", text):
        urls.append(f"https://www.quantconnect.com/tutorials/{m.group(1)}")

    return {str(STRATEGY_PHP): urls} if urls else {}


def _get_resource_redirects(doc_files: list[str]) -> dict[str, list[str]]:
    """Find all <? include(DOCS_RESOURCES."...") references."""
    resource_files: dict[str, list[str]] = {}

    for filepath in doc_files:
        try:
            with open(filepath, encoding="utf-8", errors="replace") as f:
                content = f.read()
        except Exception:
            continue

            parts = content.split('<? include(DOCS_RESOURCES."')
            for part in parts[1:]:
                sub_dir = part.split('"')[0]
                if sub_dir.startswith("/"):
                    sub_dir = sub_dir[1:]
                if not sub_dir or sub_dir.isspace() or "{" in sub_dir or "}" in sub_dir:
                    continue
                # skip if line is commented out
                line_start = part.rsplit("\n", 1)[-1] if "\n" in content.split('<? include(DOCS_RESOURCES."')[0] else ""
                resource_files.setdefault(sub_dir, []).append(filepath)

    return resource_files


# -- HTTP checking ------------------------------------------------------------

async def _check_200_response(
    resp: aiohttp.ClientResponse,
    url: str,
    files: list[str],
    is_github_issue: bool,
    results: dict[str, list[str]],
):
    """Handle additional checks when a URL returns 200 OK."""
    if is_github_issue:
        body = await resp.text()
        try:
            state = json.loads(body).get("state")
            if state != "open":
                results["github_issue_closed"].append(
                    _fmt(f"GitHub issue is not open (state: {state})", url, files))
        except json.JSONDecodeError:
            pass

    # Check for soft-404 (redirected to /404 page)
    if str(resp.url).rstrip("/").endswith("/404"):
        results["soft_404"].append(_fmt("Soft 404 (page not found)", url, files))

    # lean.io resource-only links: check GitHub folder
    if "lean.io" in url and "/docs/v2" in url and "api-reference" not in url:
        if "#" not in url and all("Resources" in f.replace("\\", "/") for f in files):
            body = await resp.text()
            if not _check_github_folder_in_html(body):
                results["invalid_docs_page"].append(
                    _fmt("Not a valid docs page", url, files))


def _fmt(msg: str, url: str, files: list[str]) -> str:
    return f"{msg}:\n\t{url}\n\t[\n\t\t{chr(10).join(files)}\n\t]"


async def _check_url(
    session: aiohttp.ClientSession,
    semaphore: asyncio.Semaphore,
    url: str,
    files: list[str],
    file_index: dict[str, list[str]],
    results: dict[str, list[str]],
):
    """Check a single URL for errors."""
    if not url or not url.startswith("http"):
        return

    # -- Deprecated docs check (no HTTP needed) --
    if f"{ROOT}docs/" in url and "/docs/v1/" not in url and "/docs/v2/" not in url:
        results["deprecated_docs"].append(_fmt("Deprecated docs URL", url, files))

    # -- lean.io non-existence check --
    if f"{LEAN_IO}docs/v2/" in url:
        if any(bad in url for bad in LEAN_IO_ERROR_URLS):
            results["leanio_nonexistence"].append(_fmt("Lean.io non-existence", url, files))

    # -- Section anchor validation (local, no HTTP needed) --
    reason = check_section_anchor(url, BASE_PATH, file_index)
    if reason:
        results["missing_section"].append(_fmt(reason, url, files))

    # -- GitHub issue API check --
    is_github_issue = "api.github.com/repos/QuantConnect/Lean/issues" in url

    # -- HTTP request --
    async with semaphore:
        try:
            async with session.get(url, allow_redirects=True, timeout=aiohttp.ClientTimeout(total=60)) as resp:
                match resp.status:
                    case 400:
                        results["400"].append(_fmt("400 Bad Request", url, files))
                    case 401:
                        results["401"].append(_fmt("401 Unauthorized", url, files))
                    case 403:
                        results["403"].append(_fmt("403 Forbidden", url, files))
                    case 404:
                        results["404"].append(_fmt("404 Not found", url, files))
                    case 200:
                        await _check_200_response(resp, url, files, is_github_issue, results)

        except Exception:
            results["failed_request"].append(_fmt("Failed to request", url, files))


def _check_github_folder_in_html(html: str) -> bool:
    """Check if HTML contains anchor-link hrefs pointing to Documentation tree with doc files."""
    pattern = r'class=["\']anchor-link["\'][^>]*href=["\']([^"\']+)["\']'
    matches = re.findall(pattern, html)
    # Also check href before class
    pattern2 = r'href=["\']([^"\']+)["\'][^>]*class=["\']anchor-link["\']'
    matches.extend(re.findall(pattern2, html))

    gh_links = [m for m in matches if m.startswith("https://github.com/QuantConnect/Documentation/tree/master/")]
    if not gh_links:
        return False
    return any(
        m.endswith(".html") or m.endswith(".php") or m.endswith(".json")
        for m in gh_links
    )


# -- Resource redirect validation ---------------------------------------------

def _check_resources(resource_files: dict[str, list[str]], results: dict[str, list[str]]):
    """Check that all resource includes point to existing files/dirs."""
    resources_dir = BASE_PATH / "Resources"
    for sub_path, files in resource_files.items():
        full = resources_dir / sub_path
        if not full.exists():
            results["missing_resource"].append(
                f'Non-existing resource page:\n\t"Resources/{sub_path}"\n\t[\n\t\t{chr(10).join(files)}\n\t]'
            )


# -- Main ---------------------------------------------------------------------

async def main():
    parser = argparse.ArgumentParser(description="Check documentation for broken links.")
    parser.add_argument("--create-issue", action="store_true",
                        help="Create/update/close a GitHub issue via `gh` CLI when errors are found.")
    args = parser.parse_args()

    start = time.perf_counter()

    print("Extracting URLs from documentation files...")
    doc_files, file_index = _collect_files()
    url_files = _get_all_urls(doc_files)
    strategy_urls = _get_strategy_php_urls()
    url_files.update(strategy_urls)

    resource_files = _get_resource_redirects(doc_files)

    count = len(url_files)
    print(f"Start Testing {count} URLs...")

    results: dict[str, list[str]] = {cat: [] for cat in SEVERITY}
    semaphore = asyncio.Semaphore(CONCURRENCY)

    connector = aiohttp.TCPConnector(limit=CONCURRENCY, limit_per_host=20)
    async with aiohttp.ClientSession(
        connector=connector,
        headers={"User-Agent": USER_AGENT},
    ) as session:
        tasks = []
        for url, files in url_files.items():
            tasks.append(_check_url(session, semaphore, url, files, file_index, results))

        # Run all checks concurrently with progress bar
        done = 0
        for coro in asyncio.as_completed(tasks):
            await coro
            done += 1
            if done % CONCURRENCY == 0 or done == count:
                filled = int(CONCURRENCY * done / count)
                bar = "#" * filled + "-" * (CONCURRENCY - filled)
                print(f"\r  [{bar}] {done}/{count} ({done/count:.1%})", end="", flush=True)

    # Check resource redirects
    print(f"\nNow check {len(resource_files)} RESOURCE redirection.")
    _check_resources(resource_files, results)

    print(f"Finished in {time.perf_counter() - start:.2f}s")

    # Separate into errors and warnings based on SEVERITY config
    error_count = 0
    warning_count = 0

    for category, items in results.items():
        if not items:
            continue
        severity = SEVERITY[category]
        label = "ERROR" if severity == "error" else "WARNING"
        if severity == "error":
            error_count += len(items)
        else:
            warning_count += len(items)

        print(f"\n{'='*60}")
        print(f"{label}S - {category} ({len(items)}):")
        print(f"{'='*60}")
        for item in items:
            print(f"  {label}: {item}")

    print(f"\n{'-'*60}")
    print(f"Summary: {error_count} error(s), {warning_count} warning(s)")
    print(f"{'-'*60}")

    if args.create_issue:
        _manage_github_issue(results, error_count > 0)

    if error_count > 0:
        print(f"\nFAILED: {error_count} broken link(s) found.")
        sys.exit(1)
    elif warning_count > 0:
        print(f"\nPASSED with {warning_count} warning(s).")
    else:
        print("\nAll links are valid!")


ISSUE_TITLE = "Fix broken documentation links"


def _find_existing_issue() -> str | None:
    """Find an open issue with the expected title. Returns the issue number or None."""
    try:
        result = subprocess.run(
            ["gh", "issue", "list",
             "--state", "open",
             "--search", ISSUE_TITLE,
             "--json", "number,title"],
            capture_output=True, text=True, cwd=BASE_PATH
        )
        if result.returncode != 0:
            return None
        for issue in json.loads(result.stdout):
            if issue["title"] == ISSUE_TITLE:
                return str(issue["number"])
    except (FileNotFoundError, json.JSONDecodeError):
        pass
    return None


def _build_issue_body(results: dict[str, list[str]]) -> str:
    """Build markdown body from error results."""
    error_sections = []
    for category, items in results.items():
        if not items or SEVERITY[category] != "error":
            continue
        lines = [f"### {category} ({len(items)})\n"]
        for item in items:
            parts = item.split("\n\t")
            msg = parts[0].rstrip(":")
            url = parts[1] if len(parts) > 1 else ""
            file_list = ""
            if len(parts) > 2:
                raw = "\n\t".join(parts[2:]).strip().strip("[]").strip()
                file_list = "\n".join(f"  - `{f.strip()}`" for f in raw.split("\n") if f.strip())
            lines.append(f"- **{msg}**: `{url}`")
            if file_list:
                lines.append(file_list)
        error_sections.append("\n".join(lines))

    return (
        "## Broken Documentation Links\n\n"
        "The URL checker found broken links that need to be fixed.\n\n"
        + "\n\n".join(error_sections)
        + "\n\n---\n"
        "After fixing the links, run `python url_check.py` locally to confirm all broken links are resolved before closing this issue.\n\n"
        "*Auto-generated by `url_check.py`*"
    )


def _manage_github_issue(results: dict[str, list[str]], has_errors: bool):
    """Create, update, or close the GitHub issue based on current errors."""
    try:
        existing = _find_existing_issue()

        if has_errors:
            body = _build_issue_body(results)
            if existing:
                result = subprocess.run(
                    ["gh", "issue", "edit", existing, "--body", body],
                    capture_output=True, text=True, cwd=BASE_PATH
                )
                if result.returncode == 0:
                    print(f"\nGitHub issue #{existing} updated.")
                else:
                    print(f"\nFailed to update issue: {result.stderr.strip()}")
            else:
                result = subprocess.run(
                    ["gh", "issue", "create",
                     "--title", ISSUE_TITLE,
                     "--body", body,
                     "--label", "bug"],
                    capture_output=True, text=True, cwd=BASE_PATH
                )
                if result.returncode == 0:
                    print(f"\nGitHub issue created: {result.stdout.strip()}")
                else:
                    print(f"\nFailed to create issue: {result.stderr.strip()}")
        else:
            if existing:
                result = subprocess.run(
                    ["gh", "issue", "close", existing,
                     "--comment", "All broken links have been fixed."],
                    capture_output=True, text=True, cwd=BASE_PATH
                )
                if result.returncode == 0:
                    print(f"\nGitHub issue #{existing} closed (no more errors).")
                else:
                    print(f"\nFailed to close issue: {result.stderr.strip()}")

    except FileNotFoundError:
        print("\nSkipping GitHub issue management: 'gh' CLI not found.")


if __name__ == "__main__":
    asyncio.run(main())
