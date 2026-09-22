"""Generate the Dataset Market price book of the pricing skill.

Writes skill-templates/pricing/references/price-book.md from three API routes:

    /market/data/list      -> the dataset products (marketing strings only).
    /market/sections/read  -> one dataset's SKUs, each with a real `price` object.
    /data/prices           -> the per-file rules `lean data download` charges by:
                              a path regex, QCC per file and the SKU it bills.

Prices are per organization tier, and `sections/read` quotes only the calling
organization's tier, so the per-tier prices come from the Pricing page catalog
(`qc_plan_prices.catalog`), which carries all four. The handful of SKUs the
catalog doesn't list keep the quoted price and are named under their table.

Filter on `purchasable`, never on cloud/cli: the on-premise history bundles are
neither cloud nor cli products and are the most expensive SKUs.

Needs QUANTCONNECT_USER_ID and QUANTCONNECT_API_TOKEN.

Usage:
    python code-generators/qc_dataset_prices.py
    python code-generators/qc_dataset_prices.py --dataset-id 17 --no-write
"""

import html
import sys
from argparse import ArgumentParser
from concurrent.futures import ThreadPoolExecutor
from datetime import date
from pathlib import Path
from urllib.error import URLError

from _code_generation_helpers import api_post
from qc_plan_prices import TIERS, TIER_LABELS, catalog

OUTPUT = Path(__file__).resolve().parents[1] / "skill-templates/pricing/references/price-book.md"
DATASET_URL = "https://www.quantconnect.com/datasets/{}/pricing"
# Deprecated SKUs, by kind, still returned by the API.
DEPRECATED_KINDS = {"Hourly and Daily History", "Hourly and Daily Updates"}


def quote_org() -> str:
    """The organization `sections/read` quotes for. Its tier only sets the price of
    the SKUs missing from the Pricing page catalog, so any organization works; a Free
    or Quant Researcher one keeps those few at list price."""
    organizations = api_post("/organizations/list").get("organizations", [])
    if not organizations:
        sys.exit("ERROR: no organization on these credentials.")
    return next((o["id"] for o in organizations if o.get("type") in ("Free", "Researcher")),
                organizations[0]["id"])


def datasets(only: list[int] | None) -> list[dict]:
    found = api_post("/market/data/list").get("list", [])
    if only:
        found = [d for d in found if d["id"] in set(only)]
        missing = set(only) - {d["id"] for d in found}
        if missing:
            sys.exit(f"ERROR: no such dataset id(s): {sorted(missing)}")
    return found


def price_rows(meta: list[dict], org_id: str, workers: int) -> list[dict]:
    """One row per SKU, quoted for `org_id`. A failed dataset raises: a missing
    row would read as a free dataset."""
    def fetch(entry: dict) -> tuple[dict, list[dict]]:
        # ~85 calls in parallel; the API drops the odd connection.
        for attempt in range(3):
            try:
                sections = api_post("/market/sections/read",
                                    {"id": entry["id"], "organizationId": org_id})["sections"]
                return entry, sections.get("products") or []
            except URLError:
                if attempt == 2:
                    raise

    rows: list[dict] = []
    with ThreadPoolExecutor(max_workers=workers) as pool:
        for entry, products in pool.map(fetch, meta):
            for sku in products:
                price = sku.get("price") or {}
                rows.append({
                    "datasetId": entry["id"], "dataset": entry.get("name"),
                    "slug": entry.get("url"), "vendor": entry.get("vendorName"),
                    "listCTA": entry.get("priceCTA"), "skuId": sku.get("id"),
                    "sku": sku.get("name"), "skuKind": sku.get("shortDescription"),
                    "cloud": sku.get("cloudProduct"), "cli": sku.get("cliProduct"),
                    "purchasable": sku.get("purchasable"),
                    "priceCTA": price.get("priceCTA"),
                    "monthly": price.get("monthlyPrice"),
                    "yearly": price.get("yearlyPrice"),
                })
    rows.sort(key=lambda r: (str(r["dataset"]).lower(), r["skuId"] or 0))
    return rows


def file_rules(org_id: str) -> list[dict]:
    """The per-file download rules, minus the 0-QCC `setup/...tar` rules, which
    are bulk-package entitlements rather than per-file prices."""
    rules = api_post("/data/prices", {"organizationId": org_id}).get("prices", [])
    return [r for r in rules
            if not all(path.startswith("setup/") for path in r.get("paths") or [])]


def money(value) -> str:
    return f"${value:,.2f}".replace(".00", "") if value else ""


def cell(value) -> str:
    """Storefront strings carry HTML entities and stray newlines."""
    return " ".join(html.unescape(str(value or "")).split()).replace("|", "/")


def file_rules_markdown(rules: list[dict], rows: list[dict]) -> list[str]:
    sku_names = {r["skuId"]: cell(r["sku"]) for r in rows}
    lines = ["## Per-file download prices", "",
             "What `lean data download` charges per file (`/data/prices`, the table the CLI "
             "itself uses). A 0 QCC price is free with the SKU's subscription (the Security "
             "Masters' factor and map files).",
             "", "| SKU | Vendor | QCC per file | USD per file |",
             "| --- | --- | --- | --- |"]
    seen = set()
    for rule in rules:
        name = sku_names.get(rule.get("productId"),
                             f"product {rule.get('productId')} (not listed in the Dataset Market)")
        key = (name, rule.get("price"))
        if key in seen:
            continue
        seen.add(key)
        qcc = rule.get("price") or 0
        lines.append(f"| {name} | {cell(rule.get('vendorName'))} | {qcc:,} | "
                     f"${qcc / 100:,.2f} |")
    return lines + [""]


def tier_cells(row: dict, tiers: dict) -> tuple[list[str], bool]:
    """One price per tier, and whether it fell back to the quoted organization's."""
    market = (tiers.get(cell(row["sku"])) or {}).get("marketPrice") or {}
    prices = [((market.get(t) or {}).get("monthly"), (market.get(t) or {}).get("yearly"))
              for t in TIERS]
    fallback = not all(yearly for _, yearly in prices)
    if fallback:
        prices = [(row["monthly"], row["yearly"])] * len(TIERS)
    # Bulk is billed annually; the API's monthly figure is never charged.
    bulk = str(row["skuKind"]).startswith("Bulk")
    return ([money(y) if bulk or not m else f"{money(m)}/mo, {money(y)}/yr"
             for m, y in prices], fallback)


def render(rows: list[dict], rules: list[dict]) -> str:
    lines = [
        "# Dataset Market price book",
        "",
        f"Generated {date.today().isoformat()} by `code-generators/qc_dataset_prices.py`. "
        "Regenerate rather than edit. `Price` is the storefront label as the generating "
        "organization sees it; the tier columns hold what each tier pays. A per-file SKU "
        "is priced in QCC per file (1 QCC = $0.01), listed in the last section. Only rows "
        "a customer can buy or use are listed.",
        "",
    ]
    tiers = {cell(it["name"]): it for it in catalog().get("dataProducts") or []}
    by_dataset: dict[int, list[dict]] = {}
    for row in rows:
        by_dataset.setdefault(row["datasetId"], []).append(row)
    for group in by_dataset.values():
        shown = [r for r in group if (r["purchasable"] or r["cloud"] or r["cli"])
                 and r["skuKind"] not in DEPRECATED_KINDS]
        if not shown:
            continue
        head = group[0]
        lines += [f"## {cell(head['dataset'])} ({cell(head['vendor'])})", "",
                  f"Page: {DATASET_URL.format(head['slug'])} · listed as "
                  f"\"{cell(head['listCTA'])}\"", "",
                  "| SKU | Kind | Price | " + " | ".join(TIER_LABELS)
                  + " | Cloud | CLI | Buyable |",
                  "| --- | --- | --- | " + " | ".join("---" for _ in TIERS)
                  + " | --- | --- | --- |"]
        quoted = []
        for r in shown:
            flags = ["yes" if r[k] else "" for k in ("cloud", "cli", "purchasable")]
            prices, fallback = tier_cells(r, tiers)
            if fallback and (r["monthly"] or r["yearly"]):
                quoted.append(cell(r["sku"]))
            lines.append(f"| {cell(r['sku'])} | {cell(r['skuKind'])} | {cell(r['priceCTA'])} | "
                         + " | ".join(prices) + " | " + " | ".join(flags) + " |")
        lines.append("")
        if quoted:
            lines += ["The Pricing page catalog carries no per-tier price for "
                      + ", ".join(quoted) + "; every tier column shows the price quoted to "
                      "the generating organization.", ""]
    return "\n".join(lines + file_rules_markdown(rules, rows))


def main() -> None:
    parser = ArgumentParser(description="Generate the Dataset Market price book.")
    parser.add_argument("--organization-id",
                        help="Org to quote for; its tier sets the prices. Defaults to "
                             "the first Free or Quant Researcher org.")
    parser.add_argument("--dataset-id", type=int, action="append",
                        help="Only this dataset id (repeatable); implies --no-write.")
    parser.add_argument("--output", type=Path, default=OUTPUT)
    parser.add_argument("--no-write", action="store_true",
                        help="Print the SKUs instead of writing the price book.")
    parser.add_argument("--workers", type=int, default=4)
    args = parser.parse_args()

    org_id = args.organization_id or quote_org()
    meta = datasets(args.dataset_id)
    rows = price_rows(meta, org_id, args.workers)
    print(f"{len(meta)} datasets, {len(rows)} SKUs, "
          f"{sum(1 for r in rows if r['purchasable'])} purchasable")

    if args.no_write or args.dataset_id:
        for r in rows:
            print(f"  {r['datasetId']:>4} {cell(r['dataset'])[:34]:<34} {cell(r['sku'])[:46]:<46} "
                  f"{cell(r['priceCTA'])[:20]:<20} {money(r['monthly']):>10} {money(r['yearly']):>12}")
        return
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(render(rows, file_rules(org_id)), encoding="utf-8", newline="\n")
    print(f"wrote {args.output}")


if __name__ == "__main__":
    main()
