<p>Resolution is the duration of time that's used to sample a data source. The <code>Resolution </code> enumeration has the following members:</p>
<div data-tree='QuantConnect.Resolution'></div>
<p>The default resolution for market data is <code>Minute</code>. To set the resolution for a security, pass the <code>resolution</code> argument when you create the security subscription.</p>
<div class='section-example-container'>
    <pre class='csharp'><?=$writingAlgorithms ? "" : "qb."?>AddEquity("SPY", Resolution.Daily);</pre>
    <pre class='python'><?=$writingAlgorithms ? "self" : "qb"?>.add_equity("SPY", Resolution.daily)</pre>
</div>