---
name: docs-style
description: Write, edit, or review QuantConnect documentation prose so it follows the repo Style Guide, covering scope, redundancy, contradictions, voice, tense, sentence length, word choice, headings, and HTML classes. Use when asked to "review the style of <page>", "check this page against the style guide", "copy-edit", "tighten the wording", "make it concise", "cut what we don't need", "standardize the voice", or when writing or rewriting any .html/.php page fragment under the numbered doc folders. Skip when the task is only code examples, metadata.json, generators, or page placement and renumbering.
---

# Documentation Style and Voice

The source of truth is [Style Guide/](../../../Style%20Guide/). This skill condenses it and adds the house conventions the guide does not cover. Read the guide file named in a rule when you need its full table.

## Content

Being concise means leaving out what the reader doesn't need, not using fewer words. Before you edit any wording, decide which sentences belong on the page.

- **Scope:** Keep only what the reader needs to act on this page's topic. Link to background, history, internals, and related topics instead of explaining them. Cut statistics that don't change what the reader does.
- **Redundancy:** State each fact once. Remove any sentence that repeats another section, the page introduction, an FAQ answer, a table on the page, or an embedded source such as an iframe or a report. Link to another page that already covers the fact instead of restating it.
- **Contradictions:** Check that numbers, dates, names, and claims agree across sections, the FAQ, `metadata.json`, related pages, and the sources. When two statements conflict, ask the owner which one is correct. Don't pick one yourself.
- **Summaries:** Don't add summary paragraphs that preview or recap sections. The headings already give the page structure.

## Voice

- Address the reader as *you*, and use the imperative for instructions. Say *we recommend*, not *it's recommended* or *QuantConnect recommends*.
- Phrase guidance positively. Tell the reader what to do instead of what to avoid, including in warnings and common-mistake lists.
- Use active voice. Passive voice is fine only when the actor is unknown or unimportant, or when active voice would blame the reader.
- Use present tense. Use *will* only for events that happen later from the reader's point of view, such as a dated migration.
- Use verbs, not nominalizations: *describes*, not *provides a description of*. Use single-word verbs: *determine*, not *figure out*.
- Software can detect, store, require, or process. It does not know, want, remember, or see.
- Use *can* for ability, *may* for permission, *might* for possibility, and *must* for necessity. Avoid *should* unless you explain why.
- Write plainly for a global audience: no idioms, metaphors, humor, or culture-specific references. See [style-guide-terminology-global-audience.md](../../../Style%20Guide/style-guide-terminology-global-audience.md).

## Sentences and paragraphs

- Use subject-verb-object order. Put the condition or location before the action: *If X, do Y.* *In the panel, click Z.*
- Keep sentences to 25 words or fewer. Split a longer sentence at its natural clause break.
- Cover one idea per paragraph, in two to five sentences.
- Use a bullet list for three or more parallel items. Use a numbered list for steps, with one action per step.
- Don't start a sentence with *It is*, *There is/are*, or a bare *This*. Name the real subject or follow *this* with a noun.
- Use the shortest correct term, after you apply the Content rules. The [concise terms](../../../Style%20Guide/style-guide-concise-terms.md) table lists replacements: *to*, not *in order to*. *Must*, not *need to* or *have to*. *And*, not *as well as*.
- Use one term per concept across the page and its siblings. See [style-guide-consistent-terminology.md](../../../Style%20Guide/style-guide-consistent-terminology.md).

## Plain prose, not AI prose

Reviewers reject text that reads as AI-generated. Write connected explanatory sentences that give the reader the context they need.

- Every sentence carries context. Replace clipped openers such as *Backtest results change.* or *The differences are not cosmetic.* with a sentence that says what changes and why.
- State what is true. Avoid contrast framing such as *X, not Y*, *not A, it is B*, and *a reason to X, not a bug to Y*.
- Don't use a colon to set up a punchline or a reveal.
- Leave out editorial commentary and advice about attitude, such as *which is the point of the change*, *the upside is*, and *treat this as*.
- Only claim a cause, a frequency, or a *most common* outcome when the data shows it. When you don't know, describe the mechanism and stop.
- Take dates, timelines, and availability from the owner of the change. Don't infer them from commit dates or data.
- When you're asked to embed or link a source, such as a report, embed or link it. Don't write a summary in its place.

## Punctuation

- Use the serial comma. End every sentence and step with a period.
- Use periods, not semicolons. Use a colon only to introduce a list, table, code block, or example.
- Don't use a slash for a choice, except in established terms like *read/write*. Don't use exclamation marks. Use question marks only in FAQ questions.

## Headings

- House convention: page file names and `<h4>` headings use Title Case, such as `04 Reporting Dates.html` and `<h4>Update Frequency</h4>`. This overrides the guide's sentence case.
- Match the heading form to the content. Use a noun phrase for concepts and references (*Coverage*, *Retired Data Points*). Use an imperative verb for tasks (*Deploy Live Algorithms*). End an FAQ heading with a question mark.
- Follow every heading with body text, and don't start that text with a pronoun that refers to the heading. Don't create a single sub-heading at any level.

## HTML conventions

- Put a member that has a Python name in both languages: `<code class="csharp">PERatio</code><code class="python">pe_ratio</code>`. The same applies to `true`/`True` and `null`/`None`. Use a single `<code>` only for names that are identical in both languages, such as class and exception names.
- Put code blocks in `<div class="section-example-container">` with `<pre class="python">` and `<pre class="csharp">`. A plain `<pre>` is only for output that is the same in both languages.
- Use `table qc-table` for tables and introduce each table with a sentence that ends in a colon.
- Mark UI and file elements with the classes in [style-guide-html-classes.md](../../../Style%20Guide/style-guide-html-classes.md): `button-name`, `field-name`, `menu-name`, `tab-name`, `public-file-name`, `new-term`, and others.
- Keep terms that are standard in LEAN docs even when the guide lists them as jargon. For example, keep *throws an exception*.

## Review workflow

1. Run the linter on the page folder or files. It reads the Avoid lists from the Style Guide tables:

   ```
   python .claude/skills/docs-style/lint.py "<page folder>"
   ```

2. Read the whole page, including the FAQ, `metadata.json`, and any embedded source, before you judge single sentences. Check the page against the Content rules. List every out-of-scope, redundant, or contradicting passage.
3. Read every file in full for what the linter can't detect: voice, humanized software, nominalizations, *should*, terminology drift between sections, dual-language `<code>`, and heading form.
4. Report the findings. Put contradictions first, then redundant and out-of-scope passages, then terminology and markup, then wording. For each finding, quote the text, name the rule, and give the fix, which is often a cut.
5. When you apply fixes, keep the facts, numbers, and links exactly as they are. Edit only the sentences you flagged, and keep the file's existing line layout.
