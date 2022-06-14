<p>The data normalization mode defines how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions'>corporate actions</a>. By default, LEAN adjusts US Equity data for splits and dividends to produce a smooth price curve. The following table describes the available data normalization modes:</p>

<?php include(DOCS_RESOURCES."/datasets/data-normalization.html"); ?>


<p>To set the data normalization mode for a security, call the <code>SetDataNormalizationMode</code> method.</p>

 <div class="section-example-container">
    <pre class="csharp">var equity = AddEquity("SPY");
equity.SetDataNormalizationMode(DataNormalizationMode.Raw);</pre>
    <pre class="python">equity = self.AddEquity("SPY", leverage=3)
equity.SetDataNormalizationMode(DataNormalizationMode.Raw)</pre>
</div>

<p>To set the data normalization mode for all securities in an algorithm, set the <code>DataNormalizationMode</code> <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Universe-Settings'>universe setting</a> before you create the security subscriptions.</p>

 <div class="section-example-container">
    <pre class="csharp">UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;</pre>
    <pre class="python">self.UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw</pre>
</div>

<p>The data normalization mode you set configures the data that LEAN passes to <code>OnData</code> and the data from <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>.</p>
