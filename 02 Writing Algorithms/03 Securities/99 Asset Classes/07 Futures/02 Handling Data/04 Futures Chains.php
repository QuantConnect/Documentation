<p><code>FuturesChain</code> objects represent and entire chain of contracts for a single underlying Future. They have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.FuturesChain"></div>

<p>To get the <code>FuturesChain</code>, index the <code>FuturesChains</code> property of the <code>Slice</code> with the continuous contract <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Example of getting the FuturesChain in OnData</pre>
    <pre class="python"># Example of getting the FuturesChain in OnData</pre>
</div>

<p>You can also loop through the <code>FuturesChains</code> property to get each <code>FuturesChain</code>.</p>
<div class="section-example-container">
    <pre class="csharp">// Example of looping through FuturesChains</pre>
    <pre class="python"># Example of looping through FuturesChains</pre>
</div>
