# CLAUDE.md

## Pull Request Rules

All pull requests must follow these rules:

1. **Create a new branch** — never work directly on master. Create a descriptive branch name for the change.
2. **Rebase with upstream master** — run `git fetch upstream && git rebase upstream/master` before pushing to ensure the branch is up to date.
3. **Only include intended files** — the PR must only contain files relevant to the change.
   - Before committing, check for any staged files and **unstage them** (`git reset HEAD`) so nothing is accidentally included.
   - Re-stage only the files that belong in the PR.
   - Flag any unrelated files before pushing.
4. **Commit message style:**
   - Start with a verb in present tense (e.g. "Fix", "Add", "Update", "Remove")
   - Reference the issue number if applicable
   - Match the style of recent commits in the repo
   - Example: `Fix broken link in Delay indicator docs (#1234)`
5. **PR format:**
   - Title: short, imperative, under 70 characters
   - Body: include a brief summary and a test plan
   - Do **not** include the "Generated with Claude Code" line in the PR body

## Documentation Writing Rules

All new or edited prose in documentation pages (`.html` and `.php` fragments under the numbered folders) must follow the `docs-style` skill in `.claude/skills/docs-style/SKILL.md`. Run its linter on the page folder before you push.

## Project Template Rules

All changes under `project-templates/` must follow these rules:

1. Observe **Pull Request Rules**
2. **Run the syntax check** on any new or modified Python template before pushing —
   `python project-templates/python/run_syntax_check.py <name>` (pass the template
   folder name to check just that file, or omit it to check all). This is the same
   check the `Syntax Tests` workflow runs on PRs that touch `project-templates/python/**`.

## Skill Developement Rules

All requests to create a skill for Writing Algorithm topics must follow these rules:

1. Observe **Pull Request Rules**
2. Create the skill in skill-templates, and save the SKILL.md observing the location of the problem the skill will solve and the proposed name. For example, the SKILL.md for `chained-universes-options` from `03 Writing Algorithms\12 Universes\03 Equity\04 Chained Universes` should be saved in `skill-templates\universes\equity\chained-universes-options\SKILL.md`. We drop the `writing-algorithms` part.
3. Use positive, simpler, better English. Follow the voice and wording rules in the `docs-style` skill.
4. A good description packs three things into those words: what the skill does, when to trigger it (concrete user phrases and contexts), and when not to. The when-to-trigger part is the load-bearing one — that's literally how Claude decides whether to consult the skill, so trigger phrases earn their keep more than prose about what's inside.
5. Use py`..`cs`..` for inline code. For exclusive sections, use
- `<!--\s*python-only\s*-->\n?(.*?)<!--\s*/python-only\s*-->\n?`
- `<!--\s*csharp-only\s*-->\n?(.*?)<!--\s*/csharp-only\s*-->\n?`
6. Avoid duplicate content. Skills must be as short as possible.
7. Code blocks as guides. Add minimum implementation. E.g., we don't see to set the start and end dates.
8. Do not add **Check list** when it is coverted by **Common Mistakes**
9. Do not add negative explaination when listing common mistakes.
10. Run `python3 skill-templates/bundle-skills.py` to validate the skill.
11. Do not add skill/** to the pull request.

# CLAUDE.md

Behavioral guidelines to reduce common LLM coding mistakes. Merge with project-specific instructions as needed.

**Tradeoff:** These guidelines bias toward caution over speed. For trivial tasks, use judgment.

## 1. Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:
- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them - don't pick silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

## 2. Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?" If yes, simplify.

## 3. Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:
- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it - don't delete it.

When your changes create orphans:
- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: Every changed line should trace directly to the user's request.

## 4. Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:
- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:
```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria ("make it work") require constant clarification.

---

**These guidelines are working if:** fewer unnecessary changes in diffs, fewer rewrites due to overcomplication, and clarifying questions come before implementation rather than after mistakes.