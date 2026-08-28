"""Push the generated supported-securities tables onto the QuantConnect dataset pages.

`Supported-Assets-Table-Code-Generator.py` and `future-table-code-generator.py` regenerate
`Resources/datasets/supported-securities/` from the symbol-properties database. The same
tables are the body of the "Supported Assets" section on a handful of dataset pages, where
they used to be pasted by hand -- so every regeneration left the website behind, silently.
This closes that gap: it renders each page's section from the repo files and writes it
through the API v2 `market/sections` endpoints.

    python code-generators/sync_supported_assets.py --dry-run
    python code-generators/sync_supported_assets.py --snapshot snapshot.json

`--dry-run` reads the live pages, reports what it would change, and writes nothing. Without
it the script writes, then re-reads and diffs to confirm -- `success: true` from these
endpoints does not mean the write happened, so the envelope is never taken as evidence.
Exit status is non-zero if any page ends up different from what was sent.

Credentials come from `QUANTCONNECT_USER_ID` / `QUANTCONNECT_API_TOKEN`, or from
`~/.lean/credentials`. `QUANTCONNECT_ORGANIZATION_ID` is required on every read; any
organization the account belongs to works, and the first one is used if the variable is
unset.

The asset count
---------------
A page states its size in four places, and pushing only the table leaves three of them
contradicting it: the table header, the Introduction sentence, the Data Summary "Asset
Coverage" row, and the sidebar Coverage field (`reach`). The other three are rewritten by
substituting the *number*, never the sentence, so reviewed wording survives.

Guard-rails
-----------
This only ever overwrites the sections named in `MAPPING`; it never creates one, and a
missing section is an error rather than something to fill in. A page can therefore not be
restructured by an automated run, only refreshed.
"""

import argparse
import json
import re
import sys
from pathlib import Path

from _code_generation_helpers import api_post, get_organization_id

RESOURCES = Path("Resources/datasets/supported-securities")
SECTION_TITLE = "Supported Assets"

CRYPTO_LEAD = "<p>The following table shows the available Cryptocurrency pairs:</p>"
CRYPTO_FUTURE_LEAD = "<p>The following table shows the available Crypto Future pairs:</p>"

# One entry per dataset page whose "Supported Assets" section is a copy of these files.
# `lead` is the sentence already on the live page -- keep it verbatim rather than rewriting
# prose reviewed elsewhere. The Futures files open with their own "(N) Futures" sentence,
# so they take an empty lead and carry their count with them (`counts: False`).
#
# Deliberately absent:
#   * cryptofuture/dydx.html      -- no dYdX dataset listing exists
#   * cfd/interactivebrokers.html, forex/interactivebrokers.html
#                                 -- datasets 25 and 26 are QuantConnect's OANDA-sourced
#                                    CFD/Forex data; the IB tables belong to the Writing
#                                    Algorithms docs, not to these listings
#   * 118 tickdata-international-futures
#                                 -- its table is hand-written, not generated
MAPPING = [
    {"product": 58, "slug": "binance-crypto-price-data",
     "files": ["crypto/binance.html"], "lead": CRYPTO_LEAD},
    {"product": 76, "slug": "binance-us-crypto-price-data",
     "files": ["crypto/binanceus.html"], "lead": CRYPTO_LEAD},
    {"product": 57, "slug": "bitfinex-crypto-price-data",
     "files": ["crypto/bitfinex.html"], "lead": CRYPTO_LEAD},
    {"product": 96, "slug": "bybit-crypto-price-data",
     "files": ["crypto/bybit.html"], "lead": CRYPTO_LEAD},
    {"product": 27, "slug": "coinbase-crypto-price-data",
     "files": ["crypto/coinbase.html"], "lead": CRYPTO_LEAD},
    {"product": 60, "slug": "kraken-crypto-price-data",
     "files": ["crypto/kraken.html"], "lead": CRYPTO_LEAD},

    {"product": 85, "slug": "binance-cryptofuture-price-data",
     "files": ["cryptofuture/binance.html"], "lead": CRYPTO_FUTURE_LEAD},
    {"product": 87, "slug": "binance-cryptofuture-margin-rate-data",
     "files": ["cryptofuture/binance.html"], "lead": CRYPTO_FUTURE_LEAD},
    {"product": 97, "slug": "bybit-cryptofuture-price-data",
     "files": ["cryptofuture/bybit.html"], "lead": CRYPTO_FUTURE_LEAD},
    {"product": 98, "slug": "bybit-cryptofuture-margin-rate-data",
     "files": ["cryptofuture/bybit.html"], "lead": CRYPTO_FUTURE_LEAD},

    {"product": 25, "slug": "quantconnect-forex",
     "files": ["forex/oanda.html"],
     "lead": "<p>The following table shows the available Forex pairs:</p>"},
    {"product": 26, "slug": "quantconnect-cfd",
     "files": ["cfd/oanda.html"],
     "lead": "<p>The following table shows the available contracts:</p>"},

    {"product": 30, "slug": "algoseek-us-futures",
     "files": ["future/supported-contracts.html"], "lead": "", "counts": False},
    {"product": 31, "slug": "algoseek-us-future-options",
     "files": ["futureoption/supported-contracts.html"], "lead": "", "counts": False,
     "hold": "the generated file says '(16) Futures Options' above 15 items -- "
             "future-table-code-generator.py counts len(FUTURE_OPTIONS) while DC is "
             "dropped, because 'Dairy' is missing from its category list. The live "
             "'(15)' is the correct number; fix the generator before syncing."},
]

for _entry in MAPPING:
    _entry.setdefault("counts", True)

_TICKER = re.compile(r"<td>([^<]+)</td>")
_ITEM = re.compile(r"<li><b[^>]*>([^<]+)</b>")
_ASSET_COVERAGE = re.compile(r"Asset Coverage.*?<td>\s*([\d,]+)\b", re.S)


# --------------------------------------------------------------------------- transport

def call(endpoint, payload=None):
    """POST to the API, turning a failure into a clean exit rather than a traceback."""
    try:
        return api_post(endpoint, payload)
    except Exception as error:
        sys.exit(f"{endpoint}: {error}")


def read_sections(product, org):
    return call("/market/sections/read",
                {"id": int(product), "organizationId": org})["sections"]


def find_section(sections, group, title):
    """Match on case and whitespace, because live titles carry typos and padding."""
    target = " ".join((title or "").split()).lower()
    for section in sections.get(group, []):
        if " ".join((section.get("title") or "").split()).lower() == target:
            return section
    return None


def masters():
    return call("/market/data/list")["list"]


# ---------------------------------------------------------------------------- rendering

def render(entry, docs):
    """The section body this page should hold: the live lead sentence, then the tables."""
    parts = [entry["lead"]] if entry["lead"] else []
    for name in entry["files"]:
        path = docs / RESOURCES / name
        if not path.exists():
            sys.exit(f"{path} does not exist. Run the table generators first.")
        parts.append(path.read_text(encoding="utf-8").strip())
    return "\n".join(parts)


def symbols(html):
    """The tickers a table lists, or the enum members a Futures list does.

    Byte comparison says *whether* a page is stale; this says *what* changed, which is the
    part worth reading in a log.
    """
    return set(_TICKER.findall(html)) or set(_ITEM.findall(html))


def substitute_count(text, old, new):
    """Swap the asset count in `text`, returning (text, hits).

    Both spellings are matched, because a page mixes them -- the Introduction says '3,320'
    while a sidebar may say '3320'. `\\b` keeps 71 from matching inside 1971 or a ticker.
    """
    hits = 0
    for spelling in dict.fromkeys([old, old.replace(",", "")]):
        text, count = re.subn(rf"\b{re.escape(spelling)}\b", new, text)
        hits += count
    return text, hits


def count_updates(entry, sections, wanted, master):
    """Sections and the `reach` field that still quote the old asset count.

    Returns (list of (title, new_content), (old_reach, new_reach) or None, note).
    """
    if not entry["counts"]:
        return [], None, None

    summary = find_section(sections, "about", "Data Summary")
    match = _ASSET_COVERAGE.search((summary or {}).get("content") or "")
    if not match:
        return [], None, "no Data Summary asset count found; counts left alone"

    # Take the old count as the page writes it -- '3,320', not 3320 -- because that is the
    # token to substitute. The surrounding sentence differs per page, and rewriting it
    # would replace prose that was reviewed elsewhere.
    old = match.group(1)
    total = len(symbols(wanted))
    new = f"{total:,}" if "," in old or total >= 1000 else str(total)
    if old == new:
        return [], None, None

    updates = []
    for title in ("Introduction", "Data Summary"):
        section = find_section(sections, "about", title)
        if section is None:
            continue
        content, hits = substitute_count(section.get("content") or "", old, new)
        if hits:
            updates.append((section["title"].strip(), content))

    reach = None
    if master.get("reach"):
        updated, hits = substitute_count(master["reach"], old, new)
        if hits:
            reach = (master["reach"], updated)

    return updates, reach, f"asset count {old} -> {new}"


# ------------------------------------------------------------------------------ the run

def plan(docs, org):
    """Everything each page needs, with nothing written yet."""
    by_id = {m["id"]: m for m in masters()}
    rows = []
    for entry in MAPPING:
        print(f"{entry['product']:>4}  {entry['slug']}  <- {', '.join(entry['files'])}")
        master = by_id.get(entry["product"])
        if master is None:
            sys.exit(f"  product {entry['product']} is not in market/data/list")
        if not master.get("editable"):
            sys.exit(f"  product {entry['product']} is not editable by this account")

        wanted = render(entry, docs)
        sections = read_sections(entry["product"], org)
        section = find_section(sections, "about", SECTION_TITLE)
        if section is None:
            # Creating it would append at the end of the group rather than in reading
            # order, so this is a person's job, not an automated run's.
            sys.exit(f"  no {SECTION_TITLE!r} section on this page; add it by hand first")

        current = section.get("content") or ""
        if current.strip() == wanted.strip():
            state = "in sync"
        else:
            have, want = symbols(current), symbols(wanted)
            added, removed = sorted(want - have), sorted(have - want)
            if added or removed:
                state = f"stale, {len(have)} -> {len(want)} entries"
                if added:
                    print(f"        +{len(added)}: {', '.join(added[:12])}"
                          f"{' ...' if len(added) > 12 else ''}")
                if removed:
                    print(f"        -{len(removed)}: {', '.join(removed[:12])}"
                          f"{' ...' if len(removed) > 12 else ''}")
            else:
                state = f"markup differs, same {len(want)} entries"

        if entry.get("hold"):
            print(f"        {state}\n        ON HOLD: {entry['hold']}")
            continue

        updates, reach, note = count_updates(entry, sections, wanted, master)
        if note:
            print(f"        {note}")

        planned = list(updates)
        if current.strip() != wanted.strip():
            # Reading order: Introduction, Data Summary, then Supported Assets last.
            planned.append((SECTION_TITLE, wanted))
        if not planned and not reach:
            print(f"        {state}")
            continue

        print(f"        {state}; writing {', '.join(t for t, _ in planned) or 'nothing'}"
              + (f"; reach -> {reach[1]!r}" if reach else ""))
        rows.append({"entry": entry, "sections": sections, "planned": planned,
                     "reach": reach})
    return rows


def write(rows, org):
    """Write every planned section, then re-read and confirm. Returns the problems found."""
    problems = []
    for row in rows:
        entry = row["entry"]
        for title, content in row["planned"]:
            section = find_section(row["sections"], "about", title)
            call("/market/sections/update/",
                 {"sectionId": int(section["id"]), "title": section["title"],
                  "content": content})
            print(f"  wrote  {entry['slug']}/{title} ({len(content)}b)")
        if row["reach"]:
            call("/market/update-master/",
                 {"productId": int(entry["product"]), "reach": row["reach"][1]})
            print(f"  wrote  {entry['slug']}/reach = {row['reach'][1]!r}")

    # The envelope is not evidence: at least two of these endpoints report success while
    # changing nothing, so everything is confirmed by reading it back.
    by_id = {m["id"]: m for m in masters()}
    for row in rows:
        entry = row["entry"]
        after = read_sections(entry["product"], org)
        for title, content in row["planned"]:
            section = find_section(after, "about", title)
            if section is None:
                problems.append(f"{entry['slug']}/{title}: missing after the write")
            elif (section.get("content") or "") != content.strip():
                problems.append(f"{entry['slug']}/{title}: does not match what was sent")
        for section_id, before in _flatten(row["sections"]).items():
            now = _flatten(after).get(section_id)
            if now is None:
                problems.append(f"{entry['slug']}: section {section_id} disappeared")
            elif now.get("content") != before.get("content") and \
                    before.get("title", "").strip() not in [t for t, _ in row["planned"]]:
                problems.append(f"{entry['slug']}: section {section_id} "
                                f"({before.get('title')}) changed but was not planned")
        if row["reach"] and by_id[entry["product"]].get("reach") != row["reach"][1]:
            problems.append(f"{entry['slug']}: reach did not take")
    return problems


def _flatten(sections):
    return {s["id"]: s
            for group in ("about", "documentation", "examples")
            for s in sections.get(group, []) if s.get("id") is not None}


def main():
    parser = argparse.ArgumentParser(
        description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--docs", default=".", help="the Documentation repo root")
    parser.add_argument("--dry-run", action="store_true", help="report, write nothing")
    parser.add_argument("--snapshot", help="save the pre-change sections to this file")
    args = parser.parse_args()

    org = get_organization_id()
    rows = plan(Path(args.docs), org)

    if args.snapshot:
        with open(args.snapshot, "w", encoding="utf-8") as fh:
            json.dump({str(r["entry"]["product"]): r["sections"] for r in rows}, fh, indent=2)
        print(f"\nsnapshot of {len(rows)} page(s) -> {args.snapshot}")

    if not rows:
        print("\nnothing to do: every page already matches the repo")
        return
    if args.dry_run:
        print(f"\ndry run: {len(rows)} page(s) would be written")
        return

    print()
    problems = write(rows, org)
    print()
    if problems:
        print("VERIFICATION FAILED:")
        for problem in problems:
            print(f"  - {problem}")
        sys.exit(1)
    print(f"verified: {len(rows)} page(s) match what was sent, nothing else changed")


if __name__ == "__main__":
    main()
