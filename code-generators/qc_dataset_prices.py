"""Generate the Dataset Market price book of the pricing skill.

Writes skill-templates/pricing/references/price-book.md from three API routes:

    /market/data/list      -> the dataset products (marketing strings only).
    /market/sections/read  -> one dataset's SKUs, each with a real `price` object.
    /data/prices           -> the per-file rules `lean data download` charges by:
                              a path regex, QCC per file and the SKU it bills.

The quote depends on the calling organization's TIER: a Trading Firm org is
quoted $31,200 for US Equity minute history, a Quant Researcher org $11,760, and
a Free org gets the Quant Researcher price on every SKU. The script quotes for
the first Free or Quant Researcher organization of the credentials, so the book
holds Quant Researcher list prices; plan-prices.md has the other tiers' prices
for the packages sold through the plan checkout.

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

OUTPUT = Path(__file__).resolve().parents[1] / "skill-templates/pricing/references/price-book.md"
DATASET_URL = "https://www.quantconnect.com/datasets/{}/pricing"
# Deprecated SKUs, by kind, still returned by the API.
DEPRECATED_KINDS = {"Hourly and Daily History", "Hourly and Daily Updates"}


def list_price_org() -> str:
    """The first Free or Quant Researcher org, whose quote is the list price."""
    for org in api_post("/organizations/list").get("organizations", []):
        if org.get("type") in ("Free", "Researcher"):
            return org["id"]
    sys.exit("ERROR: no Free or Quant Researcher organization on these credentials; "
             "pass --organization-id.")


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


def render(rows: list[dict], rules: list[dict]) -> str:
    lines = [
        "# Dataset Market price book",
        "",
        f"Generated {date.today().isoformat()} by `code-generators/qc_dataset_prices.py`. "
        "These are the Quant Researcher list prices; higher tiers pay more for many SKUs "
        "(see `plan-prices.md`). Regenerate rather than edit. `Price` is the storefront "
        "label; `Monthly` and `Yearly` are the numbers behind it; a per-file SKU is priced "
        "in QCC per file (1 QCC = $0.01), listed in the last section. Only rows a customer "
        "can buy or use are listed.",
        "",
    ]
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
                  "| SKU | Kind | Price | Monthly | Yearly | Cloud | CLI | Buyable |",
                  "| --- | --- | --- | --- | --- | --- | --- | --- |"]
        for r in shown:
            flags = ["yes" if r[k] else "" for k in ("cloud", "cli", "purchasable")]
            # Bulk is billed annually; the API's monthly figure is never charged.
            monthly = "" if str(r["skuKind"]).startswith("Bulk") else money(r["monthly"])
            lines.append(f"| {cell(r['sku'])} | {cell(r['skuKind'])} | {cell(r['priceCTA'])} | "
                         f"{monthly} | {money(r['yearly'])} | "
                         + " | ".join(flags) + " |")
        lines.append("")
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

    org_id = args.organization_id or list_price_org()
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
