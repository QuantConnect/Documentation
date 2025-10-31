<p>To create an automatic indicators for <code>AroonOscillator</code>, call the <code class='csharp'>AROON</code><code class='python'>aroon</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>AROON</code><code class='python'>aroon</code> method creates a <code>AroonOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class AroonOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AroonOscillator _aroon;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _aroon = AROON(_symbol, 10, 20);
    }

    public override void OnData(Slice data)
    {

        if (_aroon.IsReady)
        {
            // The current value of _aroon is represented by itself (_aroon)
            // or _aroon.Current.Value
            Plot("AroonOscillator", "aroon", _aroon);
            // Plot all properties of abands
            Plot("AroonOscillator", "aroonup", _aroon.AroonUp);
            Plot("AroonOscillator", "aroondown", _aroon.AroonDown);
        }
    }
}</pre>
<pre class="python">class AroonOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._aroon = self.aroon(self._symbol, 10, 20)

    def on_data(self, slice: Slice) -> None:

        if self._aroon.is_ready:
            # The current value of self._aroon is represented by self._aroon.current.value
            self.plot("AroonOscillator", "aroon", self._aroon.current.value)
            # Plot all attributes of self._aroon
            self.plot("AroonOscillator", "aroon_up", self._aroon.aroon_up.current.value)
            self.plot("AroonOscillator", "aroon_down", self._aroon.aroon_down.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.aroon">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AroonOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class AroonOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AroonOscillator _aroonoscillator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _aroonoscillator = new AroonOscillator(10, 20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _aroonoscillator.Update(bar.EndTime, bar.Close);

        if (_aroonoscillator.IsReady)
        {
            // The current value of _aroonoscillator is represented by itself (_aroonoscillator)
            // or _aroonoscillator.Current.Value
            Plot("AroonOscillator", "aroonoscillator", _aroonoscillator);
            // Plot all properties of abands
            Plot("AroonOscillator", "aroonup", _aroonoscillator.AroonUp);
            Plot("AroonOscillator", "aroondown", _aroonoscillator.AroonDown);
        }
    }
}</pre>
<pre class="python">class AroonOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._aroonoscillator = AroonOscillator(10, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._aroonoscillator.update(bar.end_time, bar.close)

        if self._aroonoscillator.is_ready:
            # The current value of self._aroonoscillator is represented by self._aroonoscillator.current.value
            self.plot("AroonOscillator", "aroonoscillator", self._aroonoscillator.current.value)
            # Plot all attributes of self._aroonoscillator
            self.plot("AroonOscillator", "aroon_up", self._aroonoscillator.aroon_up.current.value)
            self.plot("AroonOscillator", "aroon_down", self._aroonoscillator.aroon_down.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AroonOscillator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AroonOscillator">reference</a>.</p>