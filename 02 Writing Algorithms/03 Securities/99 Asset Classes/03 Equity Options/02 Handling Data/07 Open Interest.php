<p>Open interest is the number of outstanding contracts that haven't been settled. It provides a measure of investor interest and the Option liquidity, so it's a popular metric to use for contract selection. <code>OpenInterest</code> objects represent the open interest of Option contracts. They have the following properties:</p>

<div data-tree="QuantConnect.Data.Market.OpenInterest"></div>

<p>Open interest is calculated once per day. To get the open interest when it's first available, index the <code>Ticks</code> property of the <code>Slice</code> with the contract <code>Symbol</code>. To avoid issues, check if the <code>Ticks</code> object contains data for your contract before you index it with the contract <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Example of getting open interest from Ticks</pre>
    <pre class="python"># Example of getting open interest from Ticks</pre>
</div>

<p>To get the latest open interest value, use the <code>OpenInterest</code> property of the <code>Option</code> or <code>OptionContract</code>.</p>
<div class="section-example-container">
    <pre class="csharp">// Example of getting open interest from Option and OptionContract</pre>
    <pre class="python"># Example of getting open interest from Option and OptionContract</pre>
</div>
