<p>If you don't provide <code>Symbol</code> objects when you make a history request, you get <code>Slice</code> objects that contain <?=!$researchEnvironment ? "the entire universe" : "all of the assets you created subscriptions for in the notebook" ?>.</p>


<p>
  When your history request returns <code>Slice</code> objects, the <code class="csharp">Time</code><code class="python">time</code> properties of these objects are based on the <?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone, but the <code class="csharp">EndTime</code><code class="python">end_time</code> properties of the individual <code>TradeBar</code>, <code>QuoteBar</code>, and <code>Tick</code> objects are based on the <span class='python'>data time zone</span><span class='csharp'><a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a></span>. 
  The <code class="csharp">EndTime</code><code class="python">end_time</code> is the end of the sampling period and when the data is actually available. 
  For daily US Equity data, this results in data points appearing on Saturday and skipping Monday.
</p>
