"""Generate the alternative-data and alternative-data-universes SKILL.md files.

Walks the QC alternative-data dump JSON, scrapes `add_data` / `add_universe`
class references and accessing-data list markers from each dataset's section
content, then enriches the alt-data skill with C#/Python property names
fetched from the QC inspector in parallel.

Page generation lives in `Alternative-Datasets-Code-Generator.py`; this file
only writes the two SKILL.md files.
"""

import json
import re
from concurrent.futures import ThreadPoolExecutor
from pathlib import Path
from urllib.request import urlopen


DUMP_URL = "https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2024-01-02.json"
INSPECTOR_URL = (
    "https://www.quantconnect.com/services/inspector"
    "?language={lang}&type=T:QuantConnect.DataSource.{cls}"
)

ALT_UNIVERSE_SKILL_PATH = "skill-templates/datasets/alternative-data-universes/SKILL.md"
ALT_DATA_SKILL_PATH = "skill-templates/datasets/alternative-data/SKILL.md"

ALT_UNIVERSE_SKILL_DESCRIPTION = (
    "Use when selecting a dynamic Equity universe from QuantConnect/LEAN alternative-data "
    "classes with py`add_universe(<AltClass>, selector)`cs`AddUniverse<AltClass>(selector)`. "
    "Covers Brain, CoinGecko, EODHD, Quiver Quantitative, and Smart Insider universes. "
    "Skip for Morningstar fundamentals, ETF constituents, or pure indicator-driven universes."
)

ALT_DATA_SKILL_DESCRIPTION = (
    "Use when subscribing to a QuantConnect/LEAN alternative-data class via "
    "py`add_data(<AltClass>, symbol)`cs`AddData<AltClass>(symbol)` and reading the result "
    "from py`slice`cs`slice` in py`on_data`cs`OnData`. Triggers — "
    "\"is this dataset a list or single point per bar\", "
    "\"why does iterating slice[dataset_symbol] fail\", "
    "\"why does .property error on a Quiver/RegAlytics/EODHDEconomicEvents value\", "
    "missing-attribute errors after py`slice[dataset_symbol]`cs`slice[_datasetSymbol]`. "
    "Skip when — the dataset is a universe (use alternative-data-universes), Morningstar "
    "fundamentals, ETF constituents, or the price feed comes through "
    "py`add_equity`cs`AddEquity` / py`add_option`cs`AddOption` instead of "
    "py`add_data`cs`AddData`."
)

# Vendors handled by hand or by built-ins; their Universe Selection sections
# don't feed into the alt-universe skill.
PRIORITY_VENDORS = ("QuantConnect", "AlgoSeek", "Morningstar", "TickData", "OANDA")

# List-mode signatures inside an "Accessing Data" section. Quiver / RegAlytics
# use `data_points = slice[...]`; EODHD Economic Events surfaces events via
# `slice.get(Cls).get(symbol)`; USDA returns a wrapper iterated via `.data`.
LIST_ACCESS_MARKERS = (
    "data_points = slice[",
    "for data_point in data_points",
    "for event in events",
    "collection.data",
    "collection.Data",
)

# Inherited / boilerplate inspector properties to drop when reporting per-class
# fields. Filtered against the C# names; the python snake_case partner at the
# same property index gets dropped along with it.
INSPECTOR_EXCLUDED_PROPS = {
    "Underlying", "FilteredContracts", "Data", "EndTime", "DataType",
    "IsFillForward", "Time", "Symbol", "Value", "Price",
}

ADD_DATA_CLASS_RE = re.compile(r"add_data\(\s*([A-Z]\w+)")
ADD_UNIVERSE_CLASS_RE = re.compile(r"add_universe\(\s*([A-Z]\w+)")


# === Inspector ===

def _fetch_inspector_props(cls_lang):
    """Fetch property-name list for (cls, lang). Returns (cls, lang, names|None)."""
    cls, lang = cls_lang
    try:
        with urlopen(INSPECTOR_URL.format(lang=lang, cls=cls), timeout=30) as r:
            data = json.load(r)
    except Exception:
        return cls, lang, None
    return cls, lang, [p.get("property-name") for p in data.get("properties") or []]


def _inner_class_candidates(cls):
    """Singular-form candidates to try when a class has no dataset-specific props."""
    out = [cls + "DataPoint"]
    if cls.endswith("s"):
        out.append(cls[:-1])
        out.append(cls[:-1] + "DataPoint")
    return out


def _collect_class_props(class_names):
    """Return {cls: (kept_pairs, inner, inner_kept_pairs)} for every requested class.

    Pairs are (python_name, csharp_name) tuples taken from the inspector at the
    same property index for the two languages. Filtering is keyed on csharp.
    Two parallel waves: requested classes, then inner-class candidates needed
    to resolve list types whose own pair list is empty after filtering.
    """
    raw = {}

    def fetch_into(targets):
        tasks = [(c, l) for c in targets for l in ("python", "csharp")]
        with ThreadPoolExecutor(max_workers=20) as ex:
            for cls, lang, props in ex.map(_fetch_inspector_props, tasks):
                raw[(cls, lang)] = props

    fetch_into(class_names)

    def kept_pairs(cls):
        py = raw.get((cls, "python"))
        cs = raw.get((cls, "csharp"))
        if py is None or cs is None or len(py) != len(cs):
            return []
        return [(p, c) for p, c in zip(py, cs) if c not in INSPECTOR_EXCLUDED_PROPS]

    extras = set()
    for cls in class_names:
        if not kept_pairs(cls):
            extras.update(c for c in _inner_class_candidates(cls) if (c, "csharp") not in raw)
    if extras:
        fetch_into(extras)

    out = {}
    for cls in class_names:
        own = kept_pairs(cls)
        if own:
            out[cls] = (own, None, [])
            continue
        chosen_inner, chosen_pairs = None, []
        for cand in _inner_class_candidates(cls):
            cand_pairs = kept_pairs(cand)
            if cand_pairs:
                chosen_inner, chosen_pairs = cand, cand_pairs
                break
        out[cls] = ([], chosen_inner, chosen_pairs)
    return out


# === Skill builders ===

def _format_prop_pairs(pairs):
    return ", ".join(f"py`{py}`cs`{cs}`" for py, cs in pairs)


def _build_alt_universe_skill(skill_data):
    parts = [
        "---",
        "name: alternative-data-universes",
        f"description: {ALT_UNIVERSE_SKILL_DESCRIPTION}",
        "---",
        "",
        "# Alternative Data Universe Classes",
        "",
    ]
    seen = set()
    for dataset_name, classes in skill_data:
        for cls in classes:
            if cls in seen:
                continue
            seen.add(cls)
            parts.append(f"- `{cls}` — {dataset_name}")
    return "\n".join(parts).rstrip() + "\n"


def _build_alt_data_skill(skill_data, props_lookup=None):
    props_lookup = props_lookup or {}
    list_rows = []
    single_rows = []
    seen = set()
    for entry in skill_data:
        _, dataset_name, classes, is_list = entry
        for cls in classes:
            if cls in seen:
                continue
            seen.add(cls)
            kept, inner, inner_kept = props_lookup.get(cls, ([], None, []))
            if kept:
                single_rows.append((cls, dataset_name, _format_prop_pairs(kept)))
            elif inner and inner_kept:
                list_rows.append((cls, inner, dataset_name, _format_prop_pairs(inner_kept)))
            elif is_list:
                list_rows.append((cls, "", dataset_name, ""))
            else:
                # Filtered to nothing and accessing-data marker says single — the
                # payload sits on the inherited `Value` field (e.g. Fred, USEnergy).
                single_rows.append((cls, dataset_name, _format_prop_pairs([("value", "Value")])))
    list_rows.sort(key=lambda r: (r[2].lower(), r[0].lower()))
    single_rows.sort(key=lambda r: (r[1].lower(), r[0].lower()))

    parts = [
        "---",
        "name: alternative-data",
        f"description: {ALT_DATA_SKILL_DESCRIPTION}",
        "---",
        "",
        "# Alternative Data Classes",
        "",
        "Subscribe with py`add_data(<AltClass>, symbol)`cs`AddData<AltClass>(symbol)` "
        "and read from py`slice`cs`slice` in py`on_data`cs`OnData`. Indexing "
        "py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns either a single "
        "data point or a list of data points per bar — see the tables below.",
        "",
        "## Single Data Point",
        "",
        "Indexing py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns a single "
        "object; read properties directly.",
        "",
        "```python",
        "def on_data(self, slice: Slice) -> None:",
        "    if slice.contains_key(self.dataset_symbol):",
        "        data_point = slice[self.dataset_symbol]",
        "        self.log(f\"{data_point.<property>}\")",
        "```",
        "",
        "```csharp",
        "public override void OnData(Slice slice)",
        "{",
        "    if (slice.ContainsKey(_datasetSymbol))",
        "    {",
        "        var dataPoint = slice[_datasetSymbol];",
        "        Log($\"{dataPoint.<Property>}\");",
        "    }",
        "}",
        "```",
        "",
        "| Class | Dataset | Properties |",
        "| --- | --- | --- |",
    ]
    for cls, ds, props_str in single_rows:
        parts.append(f"| `{cls}` | {ds} | {props_str} |")
    parts.extend([
        "",
        "## List Type",
        "",
        "Indexing py`slice[dataset_symbol]`cs`slice[_datasetSymbol]` returns the listed "
        "outer class; iterate it and read properties off each element.",
        "",
        "```python",
        "def on_data(self, slice: Slice) -> None:",
        "    if slice.contains_key(self.dataset_symbol):",
        "        for data_point in slice[self.dataset_symbol]:",
        "            self.log(f\"{data_point.<property>}\")",
        "```",
        "",
        "```csharp",
        "public override void OnData(Slice slice)",
        "{",
        "    if (slice.ContainsKey(_datasetSymbol))",
        "    {",
        "        foreach (var dataPoint in slice[_datasetSymbol])",
        "        {",
        "            Log($\"{dataPoint.<Property>}\");",
        "        }",
        "    }",
        "}",
        "```",
        "",
        "| Class | Dataset | Properties |",
        "| --- | --- | --- |",
    ])
    for cls, inner, ds, props_str in list_rows:
        head = f"`{cls}`->`{inner}`" if inner else f"`{cls}`"
        parts.append(f"| {head} | {ds} | {props_str} |")
    parts.extend([
        "",
        "## Common mistakes",
        "",
        "- Iterating a single-point class — that index returns one object; read "
        "py`.property`cs`.Property` directly.",
        "- Reading py`.property`cs`.Property` on a list-class index — iterate first, "
        "then read each element.",
        "- Skipping the py`slice.contains_key`cs`slice.ContainsKey` guard — alt-data "
        "only lands when there's an event, so the slice may not carry the symbol every bar.",
        "- Two list-class wrappers iterate through a sub-attribute, not the value "
        "itself: `USDAFruitAndVegetables` (py`collection.data`cs`collection.Data`) and "
        "`EODHDEconomicEvents` (py`slice.get(EODHDEconomicEvents).get(symbol)`"
        "cs`slice.Get<EODHDEconomicEvents>().TryGetValue(symbol, out var events)`).",
    ])
    return "\n".join(parts) + "\n"


# === Dump traversal ===

def _ordered_sections(dataset):
    """Flatten the dataset's about + documentation entries into a {title: content} dict."""
    return {
        **{item["title"]: item["content"] for item in (dataset.get("about") or []) if item.get("title")},
        **{item["title"]: item["content"] for item in (dataset.get("documentation") or []) if item.get("title")},
    }


def _scrape_skill_data(docs):
    """Return (alt_universe_rows, alt_data_rows) by walking each dataset.

    alt_universe_rows: [(dataset_name, [classes])] — only for non-priority vendors
        whose Universe Selection section references at least one `add_universe` class.
    alt_data_rows:     [(vendor, dataset_name, [classes], is_list)] — every dataset
        whose Requesting Data section references at least one `add_data` class.
    """
    # The page generator collapses CoinAPI into QuantConnect; mirror that here so
    # both skills stay vendor-aligned with the docs.
    for d in docs:
        if d["vendorName"].strip() == "CoinAPI":
            d["vendorName"] = "QuantConnect"

    alt_universe = []
    alt_data = []
    for dataset in sorted(docs, key=lambda d: (d["vendorName"].strip(), d["name"].strip())):
        vendor = dataset["vendorName"].strip()
        name = dataset["name"].strip()
        sections = _ordered_sections(dataset)

        if vendor not in PRIORITY_VENDORS:
            uni_content = sections.get("Universe Selection", "")
            if uni_content:
                seen = set()
                classes = [m for m in ADD_UNIVERSE_CLASS_RE.findall(uni_content)
                           if not (m in seen or seen.add(m))]
                if classes:
                    alt_universe.append((name, classes))

        req_content = sections.get("Requesting Data", "")
        if req_content:
            seen = set()
            classes = [m for m in ADD_DATA_CLASS_RE.findall(req_content)
                       if not (m in seen or seen.add(m))]
            if classes:
                acc_content = sections.get("Accessing Data", "")
                is_list = any(marker in acc_content for marker in LIST_ACCESS_MARKERS)
                alt_data.append((vendor, name, classes, is_list))

    return alt_universe, alt_data


def main():
    print(f"Downloading alt-data dump from {DUMP_URL}")
    docs = json.loads(urlopen(DUMP_URL).read().decode("utf-8"))

    alt_universe_skill_data, alt_data_skill_data = _scrape_skill_data(docs)

    classes = sorted({cls for _, _, cls_list, _ in alt_data_skill_data for cls in cls_list})
    print(f"Fetching inspector properties for {len(classes)} alt-data classes...")
    props = _collect_class_props(classes)

    Path(ALT_UNIVERSE_SKILL_PATH).parent.mkdir(parents=True, exist_ok=True)
    Path(ALT_UNIVERSE_SKILL_PATH).write_text(
        _build_alt_universe_skill(alt_universe_skill_data), encoding="utf-8"
    )
    print(f"Wrote {ALT_UNIVERSE_SKILL_PATH}")

    Path(ALT_DATA_SKILL_PATH).parent.mkdir(parents=True, exist_ok=True)
    Path(ALT_DATA_SKILL_PATH).write_text(
        _build_alt_data_skill(alt_data_skill_data, props), encoding="utf-8"
    )
    print(f"Wrote {ALT_DATA_SKILL_PATH}")


if __name__ == "__main__":
    main()
