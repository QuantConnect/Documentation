<p>Minute is the densest resolution of historical Options data, but the live stream provides second resolution. To trade on the denser data in live mode and still backtest the same algorithm, set the resolution from <code class="csharp">LiveMode</code><code class="python">live_mode</code>:</p>

<div class="section-example-container">
    <pre class="csharp">var resolution = LiveMode ? Resolution.Second : Resolution.Minute;
<?=$csharpSubscription?></pre>
    <pre class="python">resolution = Resolution.SECOND if self.live_mode else Resolution.MINUTE
<?=$pythonSubscription?></pre>
</div>

<p>Second resolution data isn't available in <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history requests</a> or <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm-up periods</a>, so request Minute or a coarser resolution for those.</p>
