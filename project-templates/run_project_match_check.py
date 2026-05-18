"""Verify project templates against their QuantConnect Cloud projects.

For each entry in ``project-templates/templates.json``:

  * If ``projectIdCs`` is ``null``, create a new C# project named after the
    template, upload ``project-templates/csharp/<folder>/Main.cs``, compile,
    and run a backtest.
  * Otherwise, fetch ``Main.cs`` from the cloud project, compare with the
    local file, and if they differ upload the local copy, compile, and run a
    backtest.
  * Same flow for ``projectIdPy`` / ``project-templates/python/<folder>/main.py``.

Python and C# work runs in parallel, capped at 3 concurrent tasks per
language (6 total). Newly created project IDs are written back to
``templates.json`` so subsequent runs reuse the same projects.

Credentials come from the environment (preferred) or, as a fallback for
local runs, from ``~/.lean/credentials``:

    DOCS_TEMPLATE_USER_ID       <- user-id
    DOCS_TEMPLATE_USER_TOKEN    <- api-token
    DOCS_TEMPLATE_ORG_ID        <- organization-id  (optional in env, required
                                                     so new projects land in the
                                                     documentation org)

Usage:
    python run_project_match_check.py            # check every template
    python run_project_match_check.py <folder>   # check just one folder
"""

import argparse
import base64
import hashlib
import json
import os
import sys
import threading
import time
from concurrent.futures import Future, ThreadPoolExecutor, as_completed
from pathlib import Path
from typing import Any, NamedTuple, cast

import requests


def _load_credentials() -> tuple[str, str, str | None]:
    user_id = os.environ.get("DOCS_TEMPLATE_USER_ID")
    user_token = os.environ.get("DOCS_TEMPLATE_USER_TOKEN")
    org_id = os.environ.get("DOCS_TEMPLATE_ORG_ID")
    if user_id and user_token:
        return user_id, user_token, org_id

    creds_file = Path.home() / ".lean" / "credentials"
    if not creds_file.exists():
        raise RuntimeError(
            "Set DOCS_TEMPLATE_USER_ID and DOCS_TEMPLATE_USER_TOKEN, "
            f"or create {creds_file} via `lean login`."
        )
    file_creds = json.loads(creds_file.read_text(encoding="utf-8"))
    return (
        user_id or str(file_creds["user-id"]),
        user_token or str(file_creds["api-token"]),
        org_id or file_creds.get("organization-id"),
    )


USER_ID, USER_TOKEN, ORG_ID = _load_credentials()
BASE_URL = "https://www.quantconnect.com/api/v2"
COLLABORATOR_USER_IDS = ["alexandre_catarino"]

WORKERS_PER_LANG = 3
RETRY_ATTEMPTS = 5
COMPILE_POLL_SEC = 2
COMPILE_MAX_ATTEMPTS = 90          # ~3 minutes
BACKTEST_POLL_SEC = 10
BACKTEST_MAX_ATTEMPTS = 360        # ~1 hour
REQUEST_TIMEOUT_SEC = 60

TEMPLATES_ROOT = Path(__file__).resolve().parent
TEMPLATES_FILE = TEMPLATES_ROOT / "templates.json"

LANG_CONFIG: dict[str, dict[str, str]] = {
    "python": {
        "api_lang": "Py",
        "subdir": "python",
        "filename": "main.py",
        "project_key": "projectIdPy",
    },
    "csharp": {
        "api_lang": "C#",
        "subdir": "csharp",
        "filename": "Main.cs",
        "project_key": "projectIdCs",
    },
}

_print_lock = threading.Lock()


def log(msg: str) -> None:
    with _print_lock:
        print(msg, flush=True)


# ---------------------------------------------------------------- QC Cloud API


def _headers() -> dict[str, str]:
    ts = str(int(time.time()))
    digest = hashlib.sha256(f"{USER_TOKEN}:{ts}".encode()).hexdigest()
    auth = base64.b64encode(f"{USER_ID}:{digest}".encode()).decode()
    return {"Authorization": f"Basic {auth}", "Timestamp": ts}


def _post(path: str, payload: dict[str, Any]) -> dict[str, Any]:
    last_error: str = ""
    for attempt in range(RETRY_ATTEMPTS):
        try:
            resp = requests.post(
                BASE_URL + path,
                headers=_headers(),
                json=payload,
                timeout=REQUEST_TIMEOUT_SEC,
            )
            body = resp.json()
        except (requests.RequestException, ValueError) as exc:
            last_error = f"{type(exc).__name__}: {exc}"
            time.sleep(2)
            continue
        if body.get("success"):
            return cast(dict[str, Any], body)
        last_error = json.dumps(body.get("errors") or body)
        time.sleep(2)
    raise RuntimeError(f"{path} failed after {RETRY_ATTEMPTS} attempts: {last_error}")


def create_project(name: str, language: str) -> tuple[int, set[str]]:
    """Create a project and return (projectId, set of existing collaborator publicIds).

    The set includes the owner and anyone already attached to the project
    (e.g. auto-added org admins, or collaborators added manually before this
    run), so the caller can skip them when inviting new ones.
    """
    payload: dict[str, Any] = {"name": name, "language": language}
    if ORG_ID:
        payload["organizationId"] = ORG_ID
    body = _post("/projects/create", payload)
    project = body["projects"][0]
    existing_collaborators = {c["publicId"] for c in project.get("collaborators", [])}
    return int(project["projectId"]), existing_collaborators


def add_collaborator(project_id: int, collaborator_user_id: str) -> None:
    _post(
        "/projects/collaboration/create",
        {
            "projectId": project_id,
            "collaboratorUserId": collaborator_user_id,
            "collaborationLiveControl": True,
            "collaborationWrite": True,
        },
    )


def get_collaborators(project_id: int) -> set[str]:
    """Return publicIds of every collaborator currently on the project."""
    body = _post("/projects/collaboration/read", {"projectId": project_id})
    return {c["publicId"] for c in body.get("collaborators", [])}


def update_file(project_id: int, filename: str, content: str) -> None:
    _post("/files/update", {"projectId": project_id, "name": filename, "content": content})


def read_file(project_id: int, filename: str) -> str | None:
    body = _post("/files/read", {"projectId": project_id, "name": filename})
    files = body.get("files") or []
    if not files:
        return None
    return cast("str | None", files[0].get("content"))


def compile_project(project_id: int) -> str:
    body = _post("/compile/create", {"projectId": project_id})
    compile_id = body["compileId"]
    for _ in range(COMPILE_MAX_ATTEMPTS):
        body = _post("/compile/read", {"projectId": project_id, "compileId": compile_id})
        state = body.get("state")
        if state == "BuildSuccess":
            return cast(str, compile_id)
        if state == "BuildError":
            raise RuntimeError(f"compile failed: {body.get('logs')}")
        time.sleep(COMPILE_POLL_SEC)
    raise RuntimeError("compile timed out")


def run_backtest(project_id: int, compile_id: str, name: str) -> dict[str, Any]:
    body = _post(
        "/backtests/create",
        {"projectId": project_id, "compileId": compile_id, "backtestName": name},
    )
    bt = body["backtest"]
    if isinstance(bt, list):
        bt = bt[0]
    backtest_id = bt["backtestId"]

    for _ in range(BACKTEST_MAX_ATTEMPTS):
        body = _post("/backtests/read", {"projectId": project_id, "backtestId": backtest_id})
        bt = body["backtest"]
        if isinstance(bt, list):
            bt = bt[0]
        if bt.get("completed"):
            return cast(dict[str, Any], bt)
        time.sleep(BACKTEST_POLL_SEC)
    raise RuntimeError("backtest timed out")


def validate_backtest(bt: dict[str, Any]) -> str | None:
    if bt.get("error"):
        return f"backtest error: {bt['error']}"
    if bt.get("stacktrace"):
        return f"backtest stacktrace: {bt['stacktrace']}"
    return None


# ---------------------------------------------------------- per-template work


class CheckResult(NamedTuple):
    folder: str
    lang_key: str
    project_id: int | None
    created: bool
    ok: bool
    message: str


def process_template(entry: dict[str, Any], lang_key: str) -> CheckResult:
    cfg = LANG_CONFIG[lang_key]
    folder = entry["folder"]
    name = entry["name"]
    file_path = TEMPLATES_ROOT / cfg["subdir"] / folder / cfg["filename"]
    label = f"[{cfg['api_lang']}] {folder}"

    if not file_path.exists():
        return CheckResult(folder, lang_key, None, False, False,
                           f"missing local file {file_path}")

    local_code = file_path.read_text(encoding="utf-8")
    project_id_raw = entry.get(cfg["project_key"])
    project_id: int | None = int(project_id_raw) if project_id_raw else None
    created = False

    try:
        if not project_id:
            project_id, existing_collaborators = create_project(
                f"Template/{name} ({cfg['api_lang']})", cfg["api_lang"]
            )
            created = True
            log(f"{label}: created project {project_id}")
            cloud_code: str | None = None
        else:
            cloud_code = read_file(project_id, cfg["filename"])
            if not cloud_code:
                return CheckResult(folder, lang_key, project_id, created, False,
                                   f"could not read {cfg['filename']} from project {project_id}")
            existing_collaborators = get_collaborators(project_id)

        for collaborator in COLLABORATOR_USER_IDS:
            if collaborator in existing_collaborators:
                continue
            try:
                add_collaborator(project_id, collaborator)
                log(f"{label}: added collaborator {collaborator} to project {project_id}")
            except RuntimeError as exc:
                log(f"{label}: WARNING — could not add collaborator {collaborator}: {exc}")

        if cloud_code == local_code:
            log(f"{label}: project {project_id} matches local — skipping backtest")
            return CheckResult(folder, lang_key, project_id, created, True, "")

        if cloud_code:
            log(f"{label}: project {project_id} differs from local — uploading")
        
        update_file(project_id, cfg["filename"], local_code)
        compile_id = compile_project(project_id)
        bt = run_backtest(project_id, compile_id, f"match-check-{int(time.time())}")
        err = validate_backtest(bt)
        if err:
            return CheckResult(folder, lang_key, project_id, created, False,
                               f"project {project_id}: {err}")
        log(f"{label}: project {project_id} backtest passed")
        return CheckResult(folder, lang_key, project_id, created, True, "")
    except RuntimeError as exc:
        return CheckResult(folder, lang_key, project_id, created, False, str(exc))


# ----------------------------------------------------------- templates.json IO


def _render_templates_json(templates: list[dict[str, Any]]) -> str:
    """Serialize templates.json the way the repo stores it: tab indent, CRLF,
    list values (tags) kept inline."""
    lines = ["{", '\t"templates": [']
    for i, entry in enumerate(templates):
        lines.append("\t\t{")
        keys = list(entry)
        for j, key in enumerate(keys):
            value = entry[key]
            if isinstance(value, list):
                rendered = "[" + ", ".join(
                    json.dumps(v, ensure_ascii=False) for v in value
                ) + "]"
            else:
                rendered = json.dumps(value, ensure_ascii=False)
            tail = "," if j < len(keys) - 1 else ""
            lines.append(f"\t\t\t{json.dumps(key)}: {rendered}{tail}")
        lines.append("\t\t}" + ("," if i < len(templates) - 1 else ""))
    lines.append("\t]")
    lines.append("}")
    return "\r\n".join(lines) + "\r\n"


def save_templates(data: dict[str, Any]) -> None:
    # newline="" disables Windows CRLF translation; the renderer already emits \r\n.
    with open(TEMPLATES_FILE, "w", encoding="utf-8", newline="") as f:
        f.write(_render_templates_json(data["templates"]))


# -------------------------------------------------------------------- main


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "folder", nargs="?",
        help="Optional folder name; if given, only that template is checked.",
    )
    args = parser.parse_args()

    data = json.loads(TEMPLATES_FILE.read_text(encoding="utf-8"))
    templates: list[dict[str, Any]] = data["templates"]
    if args.folder:
        templates = [t for t in templates if t["folder"] == args.folder]
        if not templates:
            log(f"No template found with folder={args.folder!r}")
            return 1

    log(f"Checking {len(templates)} template(s) against QuantConnect Cloud"
        + (f" in org {ORG_ID}" if ORG_ID else ""))

    by_folder: dict[str, dict[str, Any]] = {t["folder"]: t for t in templates}
    results: list[CheckResult] = []

    with (
        ThreadPoolExecutor(max_workers=WORKERS_PER_LANG, thread_name_prefix="py") as py_pool,
        ThreadPoolExecutor(max_workers=WORKERS_PER_LANG, thread_name_prefix="cs") as cs_pool,
    ):
        futures: list[Future[CheckResult]] = []
        for entry in templates:
            futures.append(py_pool.submit(process_template, entry, "python"))
            futures.append(cs_pool.submit(process_template, entry, "csharp"))

        for fut in as_completed(futures):
            result = fut.result()
            results.append(result)
            if not result.ok:
                log(f"FAIL [{result.lang_key}] {result.folder}: {result.message}")

    # Persist any newly created project IDs back to templates.json.
    new_ids = False
    for result in results:
        if result.created and result.project_id:
            entry = by_folder[result.folder]
            key = LANG_CONFIG[result.lang_key]["project_key"]
            entry[key] = result.project_id
            new_ids = True
    if new_ids:
        save_templates(data)
        log(f"Updated {TEMPLATES_FILE} with newly created project IDs — commit the change.")

    failures = [r for r in results if not r.ok]
    log("=" * 60)
    log(f"Passed: {len(results) - len(failures)}/{len(results)}")
    if failures:
        log(f"Failed: {len(failures)}")
        for r in failures:
            log(f"  [{r.lang_key}] {r.folder}: {r.message}")
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
