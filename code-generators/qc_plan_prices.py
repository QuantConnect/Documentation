"""Generate the plan price list of the pricing skill.

Writes skill-templates/pricing/references/plan-prices.md from the public price
list of https://www.quantconnect.com/pricing.

Self-contained: stdlib only, no credentials. The logged-out pricing page embeds
its whole catalog as JSON in a <script> tag (packs, seats, nodes, support,
Object Store, add-ons, modules and the data subscriptions sold through the
plan checkout). Each item carries `price` plus `marketPrice`, the price per
organization tier keyed by seat type ("Researcher Seat" ... "Institution Seat").
`price` alone is wrong for tier-scaled items (it reads $0 for most data), so
this renders `marketPrice` whenever the tiers differ.

Usage:
    python code-generators/qc_plan_prices.py
    python code-generators/qc_plan_prices.py --json plan-prices.json
"""

import argparse
import html
import json
import re
import sys
import urllib.request
from datetime import date
from pathlib import Path

PRICING_URL = "https://www.quantconnect.com/pricing"
TIERS = ["Researcher Seat", "Team Seat", "Trading Firm Seat", "Institution Seat"]
TIER_LABELS = ["Quant Researcher", "Team", "Trading Firm", "Institution"]
OUTPUT = Path(__file__).resolve().parents[1] / "skill-templates/pricing/references/plan-prices.md"
UNLIMITED = 2**63 - 1
# In the catalog but not a product to quote.
DEPRECATED = {"Alpha Stream Analytics", "Tradier"}
# The catalog caps these at 1000 and 100 seats, but larger organizations are sold.
NO_SEAT_CAP = {"Trading Firm Seat", "Institution Seat"}

SECTIONS = [
    ("seats", "Seats", "One seat per member. The seat type sets the organization tier."),
    ("backtesting", "Backtesting nodes", None),
    ("research", "Research nodes", None),
    ("live", "Live trading nodes", "A `-WAW`, `-TOR` or `-NY7` suffix is the host region."),
    ("agent", "Agent (AI assistant) nodes", None),
    ("support", "Support seats", "Support seats buy human support tickets only; they carry no AI "
     "tokens (the catalog's \"N Human, N AI Tokens\" descriptions are stale, so they are omitted)."),
    ("objectStorage", "Object Store capacity", "Paid organizations include 50 MB; these replace it."),
    ("addOns", "Add-ons", None),
    ("moduleProducts", "Modules", None),
]


def catalog() -> dict:
    req = urllib.request.Request(PRICING_URL, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req, timeout=60) as resp:
        page = resp.read().decode("utf-8", "replace")
    for body in re.findall(r"<script[^>]*>\s*(\{.*?)</script>", page, re.S):
        try:
            data = json.loads(body)
        except json.JSONDecodeError:
            continue
        if isinstance(data, dict) and "packs" in data and "seats" in data:
            return data
    sys.exit(f"ERROR: no catalog JSON found on {PRICING_URL}; the page layout changed.")


def money(value) -> str:
    if value in (None, 0):
        return "-"
    return f"${value:,.2f}".replace(".00", "")


def cell(value) -> str:
    return " ".join(html.unescape(str(value or "")).split()).replace("|", "/")


def limit(value) -> str:
    return "no limit" if value in (None, UNLIMITED) else str(value)


def tier_prices(item: dict) -> list[tuple] | None:
    """(monthly, yearly) per tier, or None when every tier pays the same."""
    market = item.get("marketPrice") or {}
    if not isinstance(market, dict) or not market:
        return None
    rows = [((market.get(t) or {}).get("monthly"), (market.get(t) or {}).get("yearly"))
            for t in TIERS]
    return None if len(set(rows)) == 1 else rows


def pack_price(pack: dict, index: dict) -> tuple[str, int, int]:
    """Contents, monthly and yearly price of a pack at its minimum seat count.

    The catalog's pack `price` is for one seat. Every seat needs a support seat:
    a premium support seat covers one, Bronze covers the rest, and Bronze is free
    while the other lines cost more than $40 per seat a month ($400 a year).
    """
    seats = pack.get("min") or 1
    lines = [(seats, index[pack["seatType"]])]
    for key in ("research", "backtest", "live", "agent"):
        if part := pack.get(key):
            lines.append((part["quantity"], index[part["name"]]))
    support = pack["support"]
    bronze = index["Bronze Support"]
    premium = support["name"] != bronze["name"]
    total = {f: sum(q * it["price"][f] for q, it in lines) for f in ("monthly", "yearly")}
    free = {"monthly": total["monthly"] > 40 * seats, "yearly": total["yearly"] > 400 * seats}
    if premium:
        lines.append((support["quantity"], index[support["name"]]))
        for f in total:
            total[f] += support["quantity"] * index[support["name"]]["price"][f]
    n_bronze = seats - (support["quantity"] if premium else 0)
    for f in total:
        if not free[f]:
            total[f] += n_bronze * bronze["price"][f]
    parts = [f"{q} x {it['name']}" for q, it in lines]
    if n_bronze > 0:
        parts.append(f"{n_bronze} x Bronze Support" + (" (free)" if free["monthly"] else ""))
    return ", ".join(parts), total["monthly"], total["yearly"]


def billed(item: dict) -> str:
    interval = item.get("billingInterval") or ""
    # The catalog marks bulk History `once`, but bulk is billed annually.
    return {"once": "yearly only", "yearly": "yearly only"}.get(interval, interval)


def item_table(items: list[dict], describe: bool = True) -> list[str]:
    lines = ["| Item | CPU / RAM / storage | Monthly | Yearly | Billed | Min-max | Note |",
             "| --- | --- | --- | --- | --- | --- | --- |"]
    for it in items:
        spec = " / ".join(x for x in (
            f"{it['cpu']} CPU" if it.get("cpu") else "",
            f"{it['ram']} GB" if it.get("ram") else "",
            it.get("storageHuman") or "") if x) or "-"
        price = it.get("price") or {}
        lines.append(f"| {cell(it.get('name'))} | {spec} | {money(price.get('monthly'))} | "
                     f"{money(price.get('yearly'))} | {billed(it)} | "
                     f"{it.get('min')}-{limit(None if it.get('name') in NO_SEAT_CAP else it.get('max'))} | "
                     f"{cell(it.get('shortDescription')) if describe else ''} |")
    return lines


def data_table(items: list[dict]) -> list[str]:
    lines = ["| Package | Kind | Billed | " + " | ".join(TIER_LABELS) + " |",
             "| --- | --- | --- | " + " | ".join("---" for _ in TIERS) + " |"]
    for it in items:
        rows = tier_prices(it)
        if rows is None:
            price = it.get("price") or {}
            rows = [(price.get("monthly"), price.get("yearly"))] * len(TIERS)
        interval = it.get("billingInterval")
        # A yearly-only package is quoted by its yearly figure.
        shown = [money(y) if interval in ("once", "yearly") else f"{money(m)}/mo, {money(y)}/yr"
                 for m, y in rows]
        lines.append(f"| {cell(it.get('name'))} | {cell(it.get('shortDescription'))} | "
                     f"{billed(it)} | " + " | ".join(shown) + " |")
    return lines


def render(data: dict) -> str:
    out = [
        "# Plan price list",
        "",
        f"Generated {date.today().isoformat()} by `code-generators/qc_plan_prices.py` from the public "
        f"catalog embedded in {PRICING_URL}. Regenerate rather than edit. Prices are USD list "
        "prices before any coupon, proration or tax. `Yearly` is the price when billed "
        "annually. Where a price depends on the organization tier, every tier is shown.",
        "",
        "## Recommended packs",
        "",
        "The plan cards on the Pricing page. A pack is a suggested setup, not a fixed plan: "
        "the customer can add or remove any line in Customize Plan. Prices are at the "
        "pack's minimum seat count; each extra seat adds one seat and one Bronze Support.",
        "",
        "| Pack | Contents | Monthly | Yearly | Seats | Seat limit note |",
        "| --- | --- | --- | --- | --- | --- |",
    ]
    index = {it["name"]: it for key in ("seats", "research", "backtesting", "live", "agent", "support")
             for it in data.get(key) or []}
    for pack in data.get("packs", []):
        contents, monthly, yearly = pack_price(pack, index)
        capped = pack["seatType"] not in NO_SEAT_CAP
        out.append(f"| {cell(pack.get('sku'))} | {contents} | {money(monthly)} | "
                   f"{money(yearly)} | {pack.get('min')}-{limit(pack.get('max') if capped else None)} | "
                   f"{cell(pack.get('maxQuantityError')) if capped else ''} |")
    for key, title, note in SECTIONS:
        items = [it for it in data.get(key) or [] if it.get("name") not in DEPRECATED]
        if key == "seats":
            items.sort(key=lambda it: TIERS.index(it["name"]) if it["name"] in TIERS else len(TIERS))
        if not items:
            continue
        out += ["", f"## {title}", ""]
        if note:
            out += [note, ""]
        out += item_table(items, describe=key != "support")
    products = data.get("dataProducts") or []
    if products:
        out += ["", "## Data packages sold through the plan checkout", "",
                "The organization's tier sets the price where the columns differ. Yearly-only "
                "packages show the yearly figure; bulk History is the first year, bulk "
                "Updates every year after. A `-` means the catalog carries "
                "no number for that package: read it from `price-book.md` or the dataset's "
                "LEAN CLI price page. Per-file QCC prices and cloud-access prices of every "
                "dataset are in `price-book.md`.", ""]
        out += data_table(products)
    out.append("")
    return "\n".join(out)


def main() -> None:
    parser = argparse.ArgumentParser(description="Render the public plan price list.")
    parser.add_argument("--output", type=Path, default=OUTPUT)
    parser.add_argument("--no-write", action="store_true", help="Print instead of writing.")
    parser.add_argument("--json", type=Path, help="Also save the raw catalog.")
    args = parser.parse_args()

    data = catalog()
    if args.json:
        args.json.write_text(json.dumps(data, indent=1), encoding="utf-8")
    text = render(data)
    if args.no_write:
        sys.stdout.buffer.write(text.encode("utf-8"))
        return
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(text, encoding="utf-8", newline="\n")
    print(f"wrote {args.output}")


if __name__ == "__main__":
    main()
