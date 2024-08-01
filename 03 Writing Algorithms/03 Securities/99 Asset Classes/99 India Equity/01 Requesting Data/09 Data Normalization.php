<p>The data normalization mode defines how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/india-equity/corporate-actions'>corporate actions</a>. The data normalization mode affects the data that LEAN passes to <code class="csharp">OnData</code><code class="python">on_data</code> and the data from <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>. By default, LEAN adjusts India Equity data for splits and dividends to produce a smooth price curve, but the following data normalization modes are available:</p>

<div data-tree='QuantConnect.DataNormalizationMode' data-fields='Raw,Adjusted,SplitAdjusted,TotalReturn,RAW,ADJUSTED,SPLIT_ADJUSTED,TOTAL_RETURN'></div>

<? include(DOCS_RESOURCES."/datasets/data-normalization.php"); ?>

<p>To set the data normalization mode for a security, pass a <code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code> argument to the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method..</p>

 <div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("YESBANK", market: Market.India, dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
    <pre class="python">self._symbol = AddEquity("YESBANK", market=Market.INDIA, data_normalization_mode=DataNormalizationMode.RAW).symbol</pre>
</div>
