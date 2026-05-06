"""Deploy a series of agent tasks from a markdown prompts file and monitor them.

Unlike deploy_prompts_on_shot_failure.py:
    - API failures are logged and the run continues instead of exiting.
    - Tracks cumulative tokens (input + output) across all conversation reads.
    - Exits when the cumulative token total reaches ``TOKEN_LIMIT``
      (default 1_000_000, override via second CLI arg).

Usage:
    python deploy_prompts.py [path/to/prompt.md] [token_limit]
"""

import json
import re
import sys
from base64 import b64encode
from hashlib import sha256
from pathlib import Path
from time import sleep, time

import requests

BASE_WEB = "https://beta.quantconnect.com"
BASE_URL = f"{BASE_WEB}/api/v2"
TASK_CREATE_ENDPOINT = "/agents/tasks/create"
TASK_UPDATE_ENDPOINT = "/agents/tasks/update"
TASK_DELETE_ENDPOINT = "/agents/tasks/delete"
DEPLOY_CREATE_ENDPOINT = "/agents/deployments/create"
DEPLOY_READ_ENDPOINT = "/agents/deployments/read"
DEPLOY_STOP_ENDPOINT = "/agents/deployments/stop"
CONVERSATION_READ_ENDPOINT = "/agents/deployments/conversation/read"

POLL_INTERVAL_SEC = 10
POLL_MAX_ATTEMPTS = 60

RUNTIME_ERROR_MARKER = "The backtest ended with a runtime error."

PROMPTS_FILE = Path("prompt.md")
TOKEN_LIMIT = 1_000_000
STOP_ON_FAILURE = False

USER_ID = 0
API_TOKEN = ""
ORGANIZATION_ID = ""

# Latest known input+output tokens per deployment id. Sum is the running total.
_deployment_tokens: dict[str, int] = {}


class TokenBudgetExceeded(Exception):
    """Raised when cumulative tokens reach the configured limit."""


def load_credentials() -> dict:
    global USER_ID, API_TOKEN, ORGANIZATION_ID
    if not USER_ID or not API_TOKEN or not ORGANIZATION_ID:
        with open(Path.home() / ".lean" / "credentials") as f:
            file_creds = json.load(f)
        if not USER_ID:
            USER_ID = file_creds["user-id"]
        if not API_TOKEN:
            API_TOKEN = file_creds["api-token"]
        if not ORGANIZATION_ID:
            ORGANIZATION_ID = file_creds["organization-id"]
    return {
        "user-id": USER_ID,
        "api-token": API_TOKEN,
        "organization-id": ORGANIZATION_ID,
    }


def build_headers(user_id: int, api_token: str) -> dict:
    timestamp = str(int(time()))
    timestamped_token = f"{api_token}:{timestamp}"
    hashed = sha256(timestamped_token.encode()).hexdigest()
    auth = b64encode(f"{user_id}:{hashed}".encode()).decode()
    return {
        "Authorization": f"Basic {auth}",
        "Timestamp": timestamp,
    }


def post(endpoint: str, payload: dict, creds: dict) -> dict | None:
    """POST to the API. Logs and returns None on failure instead of exiting."""
    headers = build_headers(creds["user-id"], creds["api-token"])
    try:
        resp = requests.post(BASE_URL + endpoint, json=payload, headers=headers)
    except requests.RequestException as e:
        print(f"POST {endpoint} -> network error: {e}")
        return None
    print(f"POST {endpoint} -> HTTP {resp.status_code}")
    try:
        body = resp.json()
    except ValueError:
        print(f"  non-JSON response: {resp.text[:200]}")
        return None
    if not body.get("success"):
        print(json.dumps(body, indent=2))
        return None
    return body


def read_conversation(deployment_id: str, creds: dict) -> dict | None:
    """Read a conversation, update token totals, print, and trip the budget."""
    body = post(CONVERSATION_READ_ENDPOINT, {"deploymentId": deployment_id}, creds)
    if body is None:
        return None
    conversation = body.get("conversation") or {}
    used = (conversation.get("inputTokens") or 0) + (conversation.get("outputTokens") or 0)
    _deployment_tokens[deployment_id] = used
    total = sum(_deployment_tokens.values())
    print(f"  tokens: deployment={used} total={total}/{TOKEN_LIMIT}")
    if total >= TOKEN_LIMIT:
        raise TokenBudgetExceeded(f"Token limit {TOKEN_LIMIT} reached (total={total})")
    return body


def parse_prompts(path: Path) -> list[tuple[str, str]]:
    """Parse `### N. Title\\nbody...` blocks from a markdown file."""
    prompts: list[tuple[str, str]] = []
    title: str | None = None
    body_lines: list[str] = []

    def flush() -> None:
        if title is None:
            return
        body = "\n".join(l for l in body_lines if not l.lstrip().startswith("-->")).strip()
        if body:
            prompts.append((title, body))

    for line in path.read_text(encoding="utf-8").splitlines():
        m = re.match(r"^###\s+(.+)$", line)
        if m:
            flush()
            title = m.group(1).strip()
            body_lines = []
        elif line.startswith("## ") or line.startswith("# ") or line.startswith("---"):
            flush()
            title = None
            body_lines = []
        elif title is not None:
            body_lines.append(line)
    flush()
    return prompts


def find_failure(conversation: dict) -> tuple[list[str], dict[str, str]] | None:
    """Find the first runtime error or compile warning and return (issues, files-at-that-point)."""
    files: dict[str, str] = {}
    for message in (conversation.get("conversation") or {}).get("messages", []):
        for part in message.get("parts", []):
            if part.get("type") == "dynamic-tool" and part.get("toolName") in ("edit_file", "create_file"):
                inp = part.get("input")
                if isinstance(inp, dict):
                    file_path = inp.get("filePath")
                    content = inp.get("content")
                    if file_path and content is not None:
                        files[file_path] = content

            output = part.get("output")
            if not isinstance(output, dict):
                continue
            structured = output.get("structuredContent")
            if not isinstance(structured, dict):
                continue
            errors = structured.get("errors", [])
            warnings = structured.get("warnings", [])
            if structured.get("success") is False and RUNTIME_ERROR_MARKER in errors:
                return errors, dict(files)
            if warnings:
                return warnings, dict(files)
    return None


def format_code_section(files: dict[str, str]) -> str:
    if not files:
        return ""
    lang_by_ext = {".py": "python", ".cs": "csharp"}
    lines = ["", "## Code"]
    for path, content in files.items():
        lang = lang_by_ext.get(Path(path).suffix, "")
        lines += [f"**{path}**", f"```{lang}", content, "```"]
    return "\n".join(lines) + "\n"


def deploy_tasks(prompts: list[tuple[str, str]], creds: dict) -> list[dict]:
    """Create, rename, and deploy a task per prompt. Returns deployment records."""
    deployments = []
    for title, prompt in prompts:
        create_payload = {
            "organizationId": creds["organization-id"],
            "agentId": 47,
            "name": title,
            "prompt": prompt,
            "projectPreference": "project-each-task-deployment",
        }
        created = post(TASK_CREATE_ENDPOINT, create_payload, creds)
        if created is None:
            print(f"  skipping {title!r} - create failed")
            continue
        task = created["task"]

        updated = post(TASK_UPDATE_ENDPOINT, {"taskId": task["id"], "name": f"Prompt Test - {task['name']}"}, creds)
        if updated is not None:
            task = updated.get("task", task)

        deploy_result = post(DEPLOY_CREATE_ENDPOINT, {"taskId": task["id"]}, creds)
        if deploy_result is None:
            print(f"  skipping {title!r} - deploy failed")
            continue
        deployment_id = deploy_result["debug"]["id"]
        print(f"Deployed {task['name']!r} task={task['id']}")
        print(f"{BASE_WEB}/organization/{creds['organization-id']}/assistants/deployment/{deployment_id}")

        deployments.append({
            "title": title,
            "prompt": prompt,
            "task": task,
            "deployment_id": deployment_id,
        })
    return deployments


def monitor_deployments(deployments: list[dict], creds: dict, output_dir: Path) -> None:
    """Poll each deployment until completion, runtime error, or timeout."""
    pending = list(deployments)
    for attempt in range(1, POLL_MAX_ATTEMPTS + 1):
        if not pending:
            return
        still_pending = []
        for dep in pending:
            task_id = dep["task"]["id"]
            deployment_id = dep["deployment_id"]
            label = f"task={task_id} deployment={deployment_id}"

            deployment = post(DEPLOY_READ_ENDPOINT, {"deploymentId": deployment_id}, creds)
            if deployment is None:
                still_pending.append(dep)
                continue
            status = deployment["deployment"]["status"]
            print(f"[{attempt}] {label} status={status!r}")
            if status.lower() == "completed":
                read_conversation(deployment_id, creds)
                print(f"  completed {label}")
                continue
            if status.lower() == "inqueue":
                still_pending.append(dep)
                continue

            conversation = read_conversation(deployment_id, creds)
            if conversation is None:
                still_pending.append(dep)
                continue
            if STOP_ON_FAILURE:
                result = find_failure(conversation)
                if result:
                    issues, files = result
                    err_path = output_dir / f"{task_id}-{deployment_id}.md"
                    body = (
                        f"## Prompt\n{dep['prompt'].strip()}\n\n"
                        f"## Error\n" + "\n\n".join(issues) + "\n"
                        + format_code_section(files)
                    )
                    err_path.write_text(body, encoding="utf-8")
                    print(f"  failure - saved to {err_path}")
                    post(DEPLOY_STOP_ENDPOINT, {"deploymentId": deployment_id}, creds)
                    print(f"  stopped {label}")
                    continue

            still_pending.append(dep)

        pending = still_pending
        if pending:
            sleep(POLL_INTERVAL_SEC)

    if pending:
        print(f"{len(pending)} deployment(s) did not complete after {POLL_MAX_ATTEMPTS} attempts")


def main():
    global TOKEN_LIMIT
    creds = load_credentials()
    args = sys.argv[1:]
    prompts_file = Path(args[0]) if args else PROMPTS_FILE
    if len(args) > 1:
        TOKEN_LIMIT = int(args[1])
    prompts = parse_prompts(prompts_file)
    print(f"Loaded {len(prompts)} prompts from {prompts_file}")
    print(f"Token budget: {TOKEN_LIMIT}")
    deployments = deploy_tasks(prompts, creds)
    try:
        monitor_deployments(deployments, creds, prompts_file.resolve().parent)
    except TokenBudgetExceeded as e:
        print(f"Stopping: {e}")
        total = sum(_deployment_tokens.values())
        print(f"Final token total: {total}")


if __name__ == "__main__":
    main()
