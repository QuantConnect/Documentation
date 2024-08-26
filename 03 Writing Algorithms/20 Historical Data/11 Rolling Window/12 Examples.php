<h4>Example 1: Recalibrating ML Model</h4>
<p>When you would like to recalibrate a ML model frequently, using RollingWindow is much more efficient than historical data calls.</p>
<div class="section-example-container">
	<pre class="csharp">private RollingWindow&lt;decimal&gt; _window = new(100);
private Symbol _symbol;

public override void Initialize()
{
    _symbol = AddEquity("SPY").Symbol;
    Train(MyTrainingMethod);
}

public override void OnData(Slice slice)
{
    if (slice.Bars.ContainsKey(_symbol))
    {
        _window.Add(slice.Bars[_symbol].Price);
    }
}

private void MyTrainingMethod()
{
    if (_window.IsReady)
    {
        // your training process...
    }
}</pre>
  <pre class="python">def initialize(self):
    self.window = RollingWindow[float](100)
    self._symbol = Symbol.create("SPY", SecurityType.EQUITY, Market.USA)

def on_data(self, slice: Slice):
    if self._symbol in slice.bars:
        self.window.add(slice.bars[self._symbol].price)

def my_training_method(self):
    if self.window.is_ready:
        # df = self.pandas_converter.get_data_frame(self.list(self._window)[::-1])
        # you training method</pre>
</div>

<h4>Example 2: Relative Position As Of Previous Day's Data</h4>
<p>You may want to rank the current price compared to the previous prices. A RollingWindow to store the previous prices is useful.</p>
<div class="section-example-container">
	<pre class="csharp">private RollingWindow&lt;decimal&gt; _window = new(252);
private Symbol _symbol;

public override void Initialize()
{
    _symbol = AddEquity("SPY").Symbol;
}

public override void OnData(Slice slice)
{
    if (slice.Bars.ContainsKey(_symbol))
    {
        var current = slice.Bars[_symbol].Price;
        var rank = _window.Where(x =&gt; x <= current).Count() / 252m;
        _window.Update(current);
    }
}</pre>
  <pre class="python">def initialize(self):
    self.window = RollingWindow[float](100)
    self._symbol = Symbol.create("SPY", SecurityType.EQUITY, Market.USA)

def on_data(self, slice: Slice):
    if self._symbol in slice.bars:
        current = slice.bars[self._symbol].price
        rank = len([x for x in self.window if x <= current]) / 252
        self.window.add(current)</pre>
</div>

<? include(DOCS_RESOURCES."/rolling-window/examples.html"); ?>