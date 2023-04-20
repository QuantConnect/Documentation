<p>Fill forward means if there is no data point for the current <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>slice</a>, LEAN uses the previous data point. Fill forward is the default data setting. If you disable fill forward, you may get <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts#06-Stale-Fills'>stale fills</a> or you may see trade volume as zero.</p>

<p>To disable fill forward for a security, set the <code>fillForward</code> argument to false when you create the security subscription.</p>

<div class='section-example-container'>
    <pre class='csharp'><?=$cCode?></pre>
    <pre class='python'><?=$pyCode?></pre>
</div>
