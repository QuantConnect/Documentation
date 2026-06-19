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

# Shared docs-anchor validation, used by both url_check.py (this repo's own pages)
# and external_url_check.py (the Lean.Brokerages.*/Lean.DataSource.* repos).
#
# A /docs/v2/... URL's #fragment names a documentation page. Pages live on disk in
# a numbered-folder layout ("24 Reality Modeling/05 Brokerages/.../05 Alpaca/06
# Fees.html"), so the fragment "#06-Fees" should map to a "06 Fees.html" file under
# the path implied by the URL. This module builds an index of those files and
# checks a given URL's fragment against it - the HTTP layer can't, because the
# fragment is never sent to the server.

import os
import re
from pathlib import Path

# Section-anchor special-case replacements (applied to both expected path and
# section name) for headings whose slug isn't a plain hyphenation of the words.
SECTION_REPLACEMENTS = [
    ("Look ahead",   "Look-ahead"),
    ("look ahead",   "look-ahead"),
    ("profit loss",  "profit-loss"),
    ("out of the money", "out-of-the-money"),
    ("Built in",     "Built-in"),
    ("scikit learn", "scikit-learn"),
    ("third party",  "third-party"),
    ("Third Party",  "Third-Party"),
    ("Fine Tune",    "Fine-Tune"),
    ("Pre trade",    "Pre-trade"),
    ("pre trade",    "pre-trade"),
    ("Pre Trained",  "Pre-Trained"),
    ("chronos t5",   "chronos-t5"),
    ("C and Visual Studio", "C# and Visual Studio"),
    ("C and VS Code",       "C# and VS Code"),
    ("C and Rider",         "C# and Rider"),
    ("mixed mode consolidators", "mixed-mode consolidators"),
    ("Multi Alpha",  "Multi-Alpha"),
    ("Self Managed", "Self-Managed"),
    ("Sub Assistant", "Sub-Assistant"),
    ("Margin3F",     "Margin%3F"),
    ("Greeks3F",     "Greeks%3F"),
    ("Smile3F",      "Smile%3F"),
    ("Smoothing3F",  "Smoothing%3F"),
    ("Volatility3F", "Volatility%3F"),
]

# URLs whose anchors the path-matching heuristic mis-flags; treated as valid.
EDGE_CASE_URLS = frozenset({
    "https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/"
    "order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates"
})


def apply_replacements(text: str) -> str:
    for old, new in SECTION_REPLACEMENTS:
        text = text.replace(old, new)
    return text


def check_deprecated_path(url: str) -> str | None:
    """Flag docs URLs that use the deprecated 'our-platform' path.

    'our-platform' redirects to 'cloud-platform'/'local-platform', so such links
    still resolve, but they should be updated to the current path.
    """
    if re.search(r"/docs/v2/our-platform(?:[/#?]|$)", url):
        return "Deprecated 'our-platform' path (use 'cloud-platform' or 'local-platform')"
    return None


def _should_include(filepath: str) -> bool:
    """Keep only real documentation content files in the index."""
    return not any(part in filepath for part in (
        ".git", ".vs", "single-page", "08 Drafts",
        "Resources/indicators/", "90 QuantConnect Home",
    )) and not filepath.endswith("Documentation Updates.html")


def build_file_index(base_path: Path) -> dict[str, list[str]]:
    """Index every doc file under base_path by lowercased stem, for anchor lookup."""
    file_index: dict[str, list[str]] = {}
    for dirpath, _, filenames in os.walk(base_path):
        for fn in filenames:
            filepath = os.path.join(dirpath, fn)
            if not _should_include(filepath):
                continue
            stem = Path(fn).stem.lower()
            file_index.setdefault(stem, []).append(filepath)
    return file_index


# Market-hours pages render symbol anchors (e.g. #DE30EUR) from a JS data file
# rather than from sub-page files, so those anchors are validated against the JS.
_market_hours_symbols: dict[tuple[str, str], set[str]] = {}


def _get_market_hours_symbols(base_path: Path, asset_class: str) -> set[str]:
    key = (str(base_path), asset_class)
    if key in _market_hours_symbols:
        return _market_hours_symbols[key]
    js_file = base_path / "Resources" / "datasets" / "market-hours" / f"{asset_class}-market-hours.js"
    symbols: set[str] = set()
    if js_file.exists():
        content = js_file.read_text(encoding="utf-8")
        # Keys look like "Cfd-oanda-DE30EUR" - extract the suffix after the last hyphen group.
        for match in re.finditer(r'"[^"]*-([A-Z0-9][A-Za-z0-9]*)":', content):
            symbols.add(match.group(1))
    _market_hours_symbols[key] = symbols
    return symbols


def _is_market_hours_symbol(base_path: Path, url: str, anchor: str) -> bool:
    m = re.search(r'/asset-classes/([^/]+)/market-hours', url)
    if not m:
        return False
    return anchor in _get_market_hours_symbols(base_path, m.group(1))


def check_section_anchor(url: str, base_path: Path, file_index: dict[str, list[str]],
                         edge_case_urls: frozenset = EDGE_CASE_URLS,
                         path_aliases: tuple = ()) -> str | None:
    """Validate a /docs/v2 URL's #section anchor against the docs files on disk.

    Returns a human-readable reason if the section is missing, or None when the
    section exists OR the check doesn't apply to this URL (non-docs, api-reference,
    no fragment, or an explicit edge case).

    path_aliases is a list of (old, new) substring pairs applied to the expected
    on-disk path, so a URL that uses a path that redirects elsewhere (e.g. the
    "our-platform" alias of "cloud-platform") still matches the real page.
    """
    if "/docs/v2" not in url or "api-reference" in url or url in edge_case_urls:
        return None
    if "#" not in url:
        return None

    after_v2 = url.split("docs/v2/", 1)[1]
    expected_no_lower = after_v2.replace("/", os.sep).replace("-", " ").replace("#", os.sep)
    expected = apply_replacements(expected_no_lower).lower()
    expected_raw = expected_no_lower.lower()

    # The expected path, plus any aliased variants of it, are all acceptable.
    expected_variants = {expected, expected_raw}
    for old, new in path_aliases:
        for variant in (expected, expected_raw):
            if old in variant:
                expected_variants.add(variant.replace(old, new))

    section = url.split("#", 1)[1]
    section_name_raw = section.replace("-", " ")
    section_name = apply_replacements(section_name_raw)

    # Look up files matching the section name (try with replacements first, then raw).
    candidates = file_index.get(section_name.lower(), [])
    if not candidates:
        candidates = file_index.get(section_name_raw.lower(), [])

    if not candidates:
        if "/market-hours#" in url and _is_market_hours_symbol(base_path, url, section):
            return None
        return f'No Section "{section_name}" was found'

    # A candidate file must also sit under the path implied by the URL.
    for candidate in candidates:
        rel = os.path.relpath(candidate, base_path).replace("\\", os.sep)
        parts = rel.replace("\\", "/").split("/")
        numbered = [p for p in parts if p and p[0].isdigit() and " " in p]
        if not numbered:
            continue
        non_numbered_path = os.sep.join(
            p[p.index(" ") + 1:].strip() for p in numbered[:-1]
        )
        section_part = Path(numbered[-1]).stem
        full = f"{non_numbered_path}{os.sep}{section_part}".lower()
        if full in expected_variants:
            return None

    if "/market-hours#" in url and _is_market_hours_symbol(base_path, url, section):
        return None
    return f'No Section "{section_name}" was found'
