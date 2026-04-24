---
name: language-selection
description: Use before writing or reviewing any QuantConnect/LEAN algorithm code. A LEAN project is Python XOR C# — detect via QuantConnect MCP `read_project` if available, otherwise by whether the project contains `.cs` or `.py` files. Other Writing Algorithms skills show Python first as a display convention only; both forms are equally canonical, so never default to Python in a C# project.
---

# Language Selection in QuantConnect / LEAN

A LEAN project is either Python **or** C#, never both — `.cs` and `.py` files do not coexist. Writing the wrong language produces code that won't run.

## Detect

1. **MCP `read_project`** (if available) → use its `language` field.
2. **Any `.cs` in the project** → C#. **Any `.py`** → Python.
3. **Empty project / notebooks only** → ask. Research `.ipynb` files are always Python even in C# projects and don't indicate the algorithm language.

## Apply

- Produce every code block in the detected language only — new code, edits, examples, and also when **describing, listing, or citing** a skill (e.g. answering "which skills are you using?"). Snippets that illustrate a skill must render in the project's language, not Python by default.
- Don't show both languages.
- Other skills in this pack show Python first as formatting, not preference. Use the **C# implementation** block / equivalents table for C# projects.
- Keep API casing consistent with the language: `self.schedule.on(...)` in Python, `Schedule.On(...)` in C#.
