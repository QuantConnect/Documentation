---
name: code-quality
description: >
  Use when writing, reviewing, or refactoring QuantConnect/LEAN algorithm code
  for style correctness. Triggers: new algorithm code, code review, cleanup,
  "fix code style", "review code quality", "clean up the algorithm", redundant
  imports, subscription variable usage, comment format, blank-line rules,
  multi-line boolean layout, or catch-all error handling. Skip when only
  debugging runtime behavior or performance, unless the fix also changes style.
---
# QuantConnect Algorithm Code Quality
Apply these rules to `Main.cs` and custom data classes. The goal is compiling code that does not hide real failures.
## Imports
Preserve template using statements. Add or remove them only when a compile error proves one is needed.
## Subscription Variables
Store the Symbol returned by AddData(...).Symbol or AddEquity(...).Symbol. Pass the Symbol directly.
```csharp
// Bad
private Security _customBtc;
// Good
private Symbol _customSymbol;
_customSymbol = AddData<MyData>("TICKER", Resolution.Daily).Symbol;
```
Use Slice.ContainsKey(symbol) or slice.Get<T>(symbol) consistently with the stored Symbol.
## Comments
Use comments to clarify intent, not restate code. Full-line comments start with // plus a space, use sentence form, and start with a capital letter.
```csharp
// Bad
// schedule the monthly insight
var x = 1; //calculate price
// Good
// Schedule a monthly insight event.
var x = 1; // Calculate the weighted price.
```
## Multi-Line Boolean Expressions
Put && or || at the beginning of each continued line.
```csharp
// Bad
if (close > emaFast &&
    open > emaFast &&
    rsi > 50)
{
    return;
}
// Good
if (close > emaFast
    && open > emaFast
    && rsi > 50)
{
    return;
}
```
## Blank Lines
Use one blank line between methods, no blank lines inside method bodies, and standard C# brace style.
## Error Handling
Do not wrap algorithm logic in `try / catch` blocks. Exceptions reveal invalid state, missing data, and incorrect assumptions; catch-all handlers make bugs invisible.
```csharp
// Bad
try
{
    SetHoldings(_spy, targetWeight);
}
catch
{
}
// Good
if (targetWeight != 0)
{
    SetHoldings(_spy, targetWeight);
}
```
## Checklist
1. Template using statements are preserved.
2. Stored subscription variables are Symbol values.
3. Comments follow language-specific rules.
4. Multi-line boolean expressions follow language-specific continuation style.
5. Blank lines match language rules.
6. Algorithm code has no `try / catch`; it uses explicit guards for expected conditions.
