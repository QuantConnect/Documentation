<p>The data normalization mode defines how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/india-equity/corporate-actions'>corporate actions</a>. The data normalization mode affects the data that LEAN passes to <code>OnData</code> and the data from <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>. By default, LEAN adjusts India Equity data for splits and dividends to produce a smooth price curve, but the following data normalization modes are available:</p>

<div data-tree='QuantConnect.DataNormalizationMode' data-fields='Raw,Adjusted,SplitAdjusted,TotalReturn'></div>

<? include(DOCS_RESOURCES."/datasets/data-normalization.php"); ?>

<p>To set the data normalization mode for a security, pass a <code>dataNormalizationMode</code> argument to the <code>AddEquity</code> method..</p>

 <div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("YESBANK", market: Market.India, dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
    <pre class="python">self.symbol = AddEquity("YESBANK", market=Market.INDIA, dataNormalizationMode=DataNormalizationMode.RAW).symbol</pre>
</div>
