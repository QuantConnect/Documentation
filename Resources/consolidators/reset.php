<p>To reset a consolidator, call its <code class="csharp">Reset</code><code class="python">reset</code> method.</p>
<div class="section-example-container">
	  <pre class="python">self._consolidator.reset() 
</pre>
	  <pre class="csharp">_consolidator.Reset();</pre>
</div>

<p>
  If you are live trading Equities or backtesting Equities without the adjusted <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization">data normalization mode</a>, 
  reset your consolidators when <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits">splits</a> and <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#03-Dividends">dividends</a> occur. 
  When a split or dividend occurs while the consolidator is in the process of building a bar, the open, high, and low may reflect prices from before the split or dividend. 
  To avoid issues, call the consolidator's <code class="csharp">Reset</code><code class="python">reset</code> method and then warm it up with <code class="csharp">ScaledRaw</code><code class="python">SCALED_RAW</code> data from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>.
</p>

<div class="section-example-container">
	  <pre class="python">def on_data(self, data: Slice):
    # When a split or dividend occurs...
    if (data.splits.contains_key(self._symbol) and data.splits[self._symbol].type == SplitType.SPLIT_OCCURRED or 
        data.dividends.contains_key(self._symbol)):
        # If the consolidator is working on a bar...
        if self._consolidator.working_data:
            # Get adjusted prices for the time period of the working bar.
            history = self.history[TradeBar](self._symbol, self._consolidator.working_data.time, self.time, data_normalization_mode=DataNormalizationMode.SCALED_RAW)
            # Reset the consolidator.
            self._consolidator.reset()
            # Warm-up the consolidator with the adjusted price data.
            for bar in history:
                self._consolidator.update(bar)</pre>
	  <pre class="csharp">public override void OnData(Slice data)
{
    // When a split or dividend occurs...
    if ((data.Splits.ContainsKey(_symbol) && data.Splits[_symbol].Type == SplitType.SplitOccurred) ||
        data.Dividends.ContainsKey(_symbol))
    {
        // If the consolidator is working on a bar...
        if (_consolidator.WorkingBar != null)
        {
            // Get adjusted prices for the time period of the working bar.
            var startTime = _consolidator.WorkingBar.Time;
            var history = History&lt;TradeBar&gt;(_symbol, startTime, Time, dataNormalizationMode: DataNormalizationMode.ScaledRaw);
            // Reset the consolidator.
            _consolidator.Reset();
            // Warm-up the consolidator with the adjusted price data.
            foreach (var bar in history)
            {
                _consolidator.Update(bar);
            }
        }
    }
}</pre>
</div>
