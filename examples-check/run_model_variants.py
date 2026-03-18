"""Run backtests for all HuggingFace model variants and output a results table.

Usage:
    python examples-check/run_model_variants.py

Credentials (checked in order):
    1. Environment variables: DOCS_REGRESSION_TEST_USER_ID, DOCS_REGRESSION_TEST_USER_TOKEN
    2. LEAN CLI credentials file: ~/.lean/credentials
"""
import sys
import os
import json
import time
import base64
import hashlib
from pathlib import Path

try:
    import requests
except ImportError:
    sys.exit("Missing dependency: pip install requests")

try:
    from bs4 import BeautifulSoup
except ImportError:
    sys.exit("Missing dependency: pip install beautifulsoup4")


# ---------------------------------------------------------------------------
# Credentials & API helpers
# ---------------------------------------------------------------------------

def _load_credentials():
    user_id = os.environ.get("DOCS_REGRESSION_TEST_USER_ID", "")
    user_token = os.environ.get("DOCS_REGRESSION_TEST_USER_TOKEN", "")
    if user_id and user_token:
        return user_id, user_token
    lean_creds = Path.home() / ".lean" / "credentials"
    if lean_creds.exists():
        try:
            creds = json.loads(lean_creds.read_text())
            user_id = str(creds.get("user-id", ""))
            user_token = creds.get("api-token", "")
            if user_id and user_token:
                return user_id, user_token
        except (json.JSONDecodeError, KeyError):
            pass
    return "", ""


BASE_API = "https://www.quantconnect.com/api/v2"
USER_ID, USER_TOKEN = _load_credentials()
PYTHON_IMPORTS = "from AlgorithmImports import *\n"
MAX_RETRIES = 5
POLL_INTERVAL = 10  # seconds between status polls
BACKTEST_TIMEOUT = 1800  # seconds
SLOW_THRESHOLD = 300  # seconds; placeholder backtests over this become skip-test
# Set to a list of category names to skip (e.g. already tested ones)
SKIP_CATEGORIES = ["Sentiment Analysis"]
# Reuse an existing project instead of creating new ones (avoids 100/day limit).
# Set to an integer project ID, or None to always create fresh projects.
REUSE_PROJECT_ID = 29125823


def _headers():
    ts = str(int(time.time()))
    hashed = hashlib.sha256(f"{USER_TOKEN}:{ts}".encode()).hexdigest()
    auth = base64.b64encode(f"{USER_ID}:{hashed}".encode()).decode()
    return {"Authorization": f"Basic {auth}", "Timestamp": ts}


def api_post(endpoint, payload=None):
    return requests.post(
        f"{BASE_API}/{endpoint}",
        headers=_headers(),
        json=payload or {}
    ).json()


def clean_code(code):
    return (code
            .replace("&amp;&amp;", "&&")
            .replace("&lt;", "<")
            .replace("&gt;", ">"))


# ---------------------------------------------------------------------------
# HTML helpers
# ---------------------------------------------------------------------------

def mark_skip_test(file_path):
    """Change 'testable' to 'skip-test' on the first matching example div."""
    content = Path(file_path).read_text(encoding="utf-8")
    updated = content.replace(
        'class="section-example-container testable"',
        'class="section-example-container skip-test"',
        1  # only first occurrence
    )
    if updated != content:
        Path(file_path).write_text(updated, encoding="utf-8")
        print(f"  → Backtest exceeded {SLOW_THRESHOLD}s — marked skip-test: {Path(file_path).name}")


# ---------------------------------------------------------------------------
# Extract base code from HTML example file
# ---------------------------------------------------------------------------

def extract_python_code(file_path):
    """Return the first testable Python QCAlgorithm code block in the file."""
    html = Path(file_path).read_text(encoding="utf-8")
    soup = BeautifulSoup(html, "html.parser")
    for div in soup.find_all("div", class_="section-example-container"):
        classes = div.get("class", [])
        if "skip-test" in classes:
            continue
        for pre in div.find_all("pre", class_="python"):
            code = pre.get_text()
            if "(QCAlgorithm)" in code:
                return clean_code(code)
    raise ValueError(f"No testable Python QCAlgorithm block found in {file_path}")


# ---------------------------------------------------------------------------
# QC Cloud: create project, compile, backtest
# ---------------------------------------------------------------------------

def create_project_and_upload(code, label):
    if REUSE_PROJECT_ID:
        project_id = REUSE_PROJECT_ID
    else:
        ts = int(time.time())
        name = f"DocTest/variants_{ts}_{label[:20]}"
        resp = api_post("projects/create", {"name": name, "language": "Py"})
        if not (resp.get("success") or resp.get("projects")):
            raise RuntimeError(f"project create failed: {resp}")
        project_id = resp["projects"][0]["projectId"]

    full_code = PYTHON_IMPORTS + code
    for _ in range(MAX_RETRIES):
        r = api_post("files/update", {
            "projectId": project_id,
            "name": "main.py",
            "content": full_code
        })
        if r.get("success"):
            break
        time.sleep(3)
    else:
        raise RuntimeError("file upload failed")

    return project_id


def compile_project(project_id):
    resp = api_post("compile/create", {"projectId": project_id})
    if not resp.get("success"):
        raise RuntimeError(f"compile create failed: {resp}")
    compile_id = resp.get("compileId") or resp.get("compile", {}).get("compileId")
    if not compile_id:
        raise RuntimeError(f"no compileId in response: {resp}")

    deadline = time.time() + 300
    while time.time() < deadline:
        time.sleep(POLL_INTERVAL)
        r = api_post("compile/read", {"projectId": project_id, "compileId": compile_id})
        state = r.get("state") or r.get("compile", {}).get("state", "")
        if state == "BuildSuccess":
            return compile_id
        if state == "BuildError":
            logs = r.get("logs") or r.get("compile", {}).get("logs", [])
            raise RuntimeError(f"compile error: {logs[-3:] if logs else r}")
    raise RuntimeError("compile timed out")


def run_backtest(project_id, compile_id, label):
    resp = api_post("backtests/create", {
        "projectId": project_id,
        "compileId": compile_id,
        "backtestName": label[:64]
    })
    if not resp.get("success"):
        raise RuntimeError(f"backtest create failed: {resp}")
    bt_id = resp["backtest"]["backtestId"]

    deadline = time.time() + BACKTEST_TIMEOUT
    while time.time() < deadline:
        time.sleep(POLL_INTERVAL)
        r = api_post("backtests/read", {"projectId": project_id, "backtestId": bt_id})
        bt = r.get("backtest", {})
        progress = bt.get("progress", 0)
        completed = bt.get("completed", False)
        if completed:
            return bt
        if bt.get("error"):
            raise RuntimeError(f"backtest error: {bt['error']}")
        print(f"    ... {int(progress*100)}% complete")
    raise RuntimeError("backtest timed out")


def extract_stats(bt):
    # 'statistics' has the full set of metrics we need
    stats = bt.get("statistics") or {}

    def get(key, fallback="N/A"):
        val = stats.get(key, fallback)
        return val if val not in ("", None) else fallback

    return {
        "net_profit": get("Net Profit"),
        "drawdown":   get("Drawdown"),
        "sharpe":     get("Sharpe Ratio"),
    }


# ---------------------------------------------------------------------------
# Model variants per category
# ---------------------------------------------------------------------------

BASE = Path(__file__).parent.parent

CATEGORIES = [
    {
        "name": "Sentiment Analysis",
        "file": BASE / "03 Writing Algorithms/31 Machine Learning/04 Hugging Face/02 Popular Models/06 Sentiment Analysis/99 Examples.html",
        "placeholder": "mrm8488/distilroberta-finetuned-financial-news-sentiment-analysis",
        "models": [
            "mrm8488/distilroberta-finetuned-financial-news-sentiment-analysis",
            "ahmedrachid/FinancialBERT-Sentiment-Analysis",
            "AventIQ-AI/sentiment-analysis-for-stock-market-sentiment",
            "bardsai/finance-sentiment-fr-base",
            "cardiffnlp/twitter-roberta-base-sentiment-latest",
            "nickmuchi/deberta-v3-base-finetuned-finance-text-classification",
            "nickmuchi/distilroberta-finetuned-financial-text-classification",
            "nickmuchi/sec-bert-finetuned-finance-classification",
            "StephanAkkerman/FinTwitBERT-sentiment",
        ],
    },
    {
        "name": "Fill-Mask",
        "file": BASE / "03 Writing Algorithms/31 Machine Learning/04 Hugging Face/02 Popular Models/07 Fill-Mask/99 Examples.html",
        "placeholder": "distilbert/distilbert-base-uncased",
        "models": [
            "distilbert/distilbert-base-uncased",
            "FacebookAI/roberta-base",
            "google-bert/bert-base-uncased",
            "microsoft/deberta-base",
        ],
    },
    {
        "name": "Text Generation",
        "file": BASE / "03 Writing Algorithms/31 Machine Learning/04 Hugging Face/02 Popular Models/08 Text Generation/99 Examples.html",
        "placeholder": "openai-community/gpt2",
        "models": [
            "openai-community/gpt2",
            "google/gemma-7b",
            "deepseek-ai/DeepSeek-R1-Distill-Llama-70B",
        ],
    },
    {
        "name": "Chronos-Bolt",
        "file": BASE / "03 Writing Algorithms/31 Machine Learning/04 Hugging Face/02 Popular Models/12 Chronos-Bolt/99 Examples.html",
        "placeholder": "autogluon/chronos-bolt-base",
        "models": [
            "autogluon/chronos-bolt-base",
        ],
    },
]


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main():
    if not USER_ID or not USER_TOKEN:
        sys.exit(
            "Error: Set DOCS_REGRESSION_TEST_USER_ID and "
            "DOCS_REGRESSION_TEST_USER_TOKEN environment variables."
        )

    resp = api_post("authenticate")
    if not resp.get("success"):
        sys.exit("API authentication failed. Check your credentials.")
    print("API authentication successful.\n")

    results = []  # list of (category, model, stats_dict | error_str)

    for cat in CATEGORIES:
        if cat["name"] in SKIP_CATEGORIES:
            print(f"\nSkipping: {cat['name']}")
            continue
        print(f"\n{'='*60}")
        print(f"Category: {cat['name']}")
        print(f"{'='*60}")

        try:
            base_code = extract_python_code(str(cat["file"]))
        except Exception as e:
            print(f"  ERROR reading code: {e}")
            for model in cat["models"]:
                results.append((cat["name"], model, f"code read error: {e}"))
            continue

        for model in cat["models"]:
            label = f"{cat['name'][:8]}_{model.split('/')[-1][:20]}"
            print(f"\n  Model: {model}")
            try:
                code = base_code.replace(cat["placeholder"], model)

                print(f"  Creating project...")
                project_id = create_project_and_upload(code, label)

                print(f"  Compiling (project {project_id})...")
                compile_id = compile_project(project_id)

                print(f"  Running backtest...")
                bt_start = time.time()
                bt = run_backtest(project_id, compile_id, label)
                bt_elapsed = time.time() - bt_start

                if model == cat["placeholder"] and bt_elapsed > SLOW_THRESHOLD:
                    mark_skip_test(str(cat["file"]))

                stats = extract_stats(bt)
                print(f"  PASS — net profit={stats['net_profit']}  "
                      f"drawdown={stats['drawdown']}  sharpe={stats['sharpe']}  "
                      f"({int(bt_elapsed)}s)")
                results.append((cat["name"], model, stats))

            except Exception as e:
                import traceback
                traceback.print_exc()
                print(f"  FAIL — {e}")
                results.append((cat["name"], model, f"FAIL: {e}"))

    # -----------------------------------------------------------------------
    # Print results table
    # -----------------------------------------------------------------------
    print("\n\n" + "="*80)
    print("RESULTS TABLE (Markdown)")
    print("="*80)
    print()

    # Group by category
    categories_seen = []
    rows_by_cat = {}
    for cat_name, model, stats in results:
        if cat_name not in rows_by_cat:
            rows_by_cat[cat_name] = []
            categories_seen.append(cat_name)
        rows_by_cat[cat_name].append((model, stats))

    for cat_name in categories_seen:
        rows = rows_by_cat[cat_name]
        print(f"### {cat_name}\n")
        print("| Model | Net Profit | Drawdown | Sharpe Ratio |")
        print("|-------|-----------|----------|--------------|")
        for model, stats in rows:
            if isinstance(stats, dict):
                np_ = stats['net_profit']
                dd  = stats['drawdown']
                sr  = stats['sharpe']
                print(f"| `{model}` | {np_} | {dd} | {sr} |")
            else:
                print(f"| `{model}` | {stats} | — | — |")
        print()

    # Also dump raw JSON for debugging
    output_path = Path(__file__).parent / "model_variant_results.json"
    with open(output_path, "w") as f:
        json.dump(results, f, indent=2, default=str)
    print(f"Raw results saved to {output_path}")


if __name__ == "__main__":
    main()
