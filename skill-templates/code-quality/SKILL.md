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
Apply these rules to py`main.py`cs`Main.cs` and custom data classes. The goal is compiling code that does not hide real failures.
## Imports
<!-- python-only -->Use AlgorithmImports for LEAN APIs and common algorithm types. Do not add redundant imports for datetime, timedelta, date, pandas, numpy, or math. Put required standard library imports before AlgorithmImports, separated by one blank line.
<!-- /python-only -->
<!-- csharp-only -->Preserve template using statements. Add or remove them only when a compile error proves one is needed.
<!-- /csharp-only -->
## Subscription Variables
<!-- python-only -->Store the Security returned by add_data, add_equity, or add_crypto. Pass that Security directly anywhere a Symbol is expected; do not store or use .symbol.
<!-- /python-only -->
<!-- csharp-only -->Store the Symbol returned by AddData(...).Symbol or AddEquity(...).Symbol. Pass the Symbol directly.
<!-- /csharp-only -->
```python
# Bad
self._custom_symbol = self.add_data(MyData, "TICKER").symbol
data[self._custom_btc.symbol]
# Good
self._custom_btc = self.add_data(MyData, "TICKER")
if self._custom_btc not in data:
    return
bar = data[self._custom_btc]
```
```csharp
// Bad
private Security _customBtc;
// Good
private Symbol _customSymbol;
_customSymbol = AddData<MyData>("TICKER", Resolution.Daily).Symbol;
```
<!-- python-only -->Use x in data and x not in data, not data.contains_key(x).
<!-- /python-only -->
<!-- csharp-only -->Use Slice.ContainsKey(symbol) or slice.Get<T>(symbol) consistently with the stored Symbol.
<!-- /csharp-only -->
## Comments
<!-- python-only -->Follow PEP 8: block comments start with # , use complete sentences, start sentences with capitals, and end sentences with periods. Inline comments are rare, separated from code by at least two spaces, and start with # .
<!-- /python-only -->
<!-- csharp-only -->Use comments to clarify intent, not restate code. Full-line comments start with // plus a space, use sentence form, and start with a capital letter.
<!-- /csharp-only -->
```python
# Bad
# add equity
x = price*quantity #calculate value
# Good
# Add the equity subscription.
x = price * quantity  # Calculate the order value.
```
```csharp
// Bad
// schedule the monthly insight
var x = 1; //calculate price
// Good
// Schedule a monthly insight event.
var x = 1; // Calculate the weighted price.
```
## Multi-Line Boolean Expressions
<!-- python-only -->Put and/or at the end of each continued line.
<!-- /python-only -->
<!-- csharp-only -->Put && or || at the beginning of each continued line.
<!-- /csharp-only -->
```python
# Bad
if (
    close > ema_fast
    and open_ > ema_fast
    and rsi > 50):
    return
# Good
if (close > ema_fast and
    open_ > ema_fast and
    rsi > 50):
    return
```
## Blank Lines
<!-- python-only -->Use two blank lines before top-level classes, one blank line before methods inside classes, and no blank lines inside method bodies.
<!-- /python-only -->
<!-- csharp-only -->Use one blank line between methods, no blank lines inside method bodies, and standard C# brace style.
<!-- /csharp-only -->
## Error Handling
Do not wrap algorithm logic in py`try / except`cs`try / catch` blocks. Exceptions reveal invalid state, missing data, and incorrect assumptions; catch-all handlers make bugs invisible.
```python
# Bad
try:
    self.set_holdings(self._spy, target_weight)
except Exception:
    pass
# Good
if target_weight:
    self.set_holdings(self._spy, target_weight)
```
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
<!-- python-only -->1. Standard library imports precede AlgorithmImports.
2. Stored subscription variables are Security objects, not .symbol values.
<!-- /python-only -->
<!-- csharp-only -->1. Template using statements are preserved.
2. Stored subscription variables are Symbol values.
<!-- /csharp-only -->
3. Comments follow language-specific rules.
4. Multi-line boolean expressions follow language-specific continuation style.
5. Blank lines match language rules.
6. Algorithm code has no py`try / except`cs`try / catch`; it uses explicit guards for expected conditions.
