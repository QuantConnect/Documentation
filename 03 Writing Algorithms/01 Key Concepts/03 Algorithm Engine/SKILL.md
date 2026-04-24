---
name: language-selection
description: Use before writing or reviewing any QuantConnect/LEAN algorithm code. A LEAN project is Python XOR C# — detect via QuantConnect MCP `read_project` if available, otherwise by whether the project has `main.py` (Python) or any `.cs` file (C#). Other Writing Algorithms skills show Python first as a display convention only; both forms are equally canonical, so never default to Python in a C# project.
---

# Language Selection in QuantConnect / LEAN

A LEAN project is Python **or** C#, never both. Writing the wrong language produces code that won't run.

## Detect

1. **MCP `read_project`** (if available) → use its `language` field.
2. **Otherwise:** `main.py` → Python; any `.cs` file → C#. `.ipynb` doesn't count — research notebooks are always Python even in C# projects.

## Apply

This skill is **silent** — the user already knows their project language. Don't announce the detection, don't preface with "since this is a C# project…", don't ask for confirmation. Just produce the right language in every code block, including when describing or citing another skill (e.g. "which skills are you using?"). Never show both languages.

- Other skills in this pack show Python first as formatting, not preference — for C# projects use each skill's **C# implementation** block or equivalents table.
- Keep API casing consistent with the language: `self.schedule.on(...)` in Python, `Schedule.On(...)` in C#.

**Only exception — missing `main.py`:** if the project is Python but `main.py` is absent, add `main.py` and tell the user it's mandatory as LEAN's Python entrypoint. This is the sole case where language is mentioned in the reply.
