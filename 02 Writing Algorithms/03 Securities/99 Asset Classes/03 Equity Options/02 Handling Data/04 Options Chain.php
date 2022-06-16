<p><code>OptionChain</code> objects represents entire chain of Option contracts for a single underlying security. They have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.OptionChain"></div>


<p>To get the <code>OptionChain</code>, index the <code>OptionChains</code> property of the <code>Slice</code> with the canonical <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Example of getting the OptionChain in OnData</pre>
    <pre class="python"># Example of getting the OptionChain in OnData</pre>
</div>

<p>You can also loop through each <code>OptionChains</code> to get each <code>OptionChain</code>.</p>
<div class="section-example-container">
    <pre class="csharp">// Example of looping through OptionChains</pre>
    <pre class="python"># Example of looping through OptionChains</pre>
</div>