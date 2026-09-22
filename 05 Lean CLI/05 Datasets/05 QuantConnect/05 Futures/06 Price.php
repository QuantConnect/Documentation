<p>Both the bulk and by-ticker downloads require the <a href="https://www.quantconnect.com/datasets/quantconnect-us-futures-security-master/pricing">US Futures Security Master</a>. The following table shows the cost of an annual subscription to the US Futures Security Master for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/us-futures-security-master-price.html"); ?>

<h4>Download in Bulk</h4>
<p>To download the US Futures dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price depends on your organization tier and the resolution you need. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-future-universe">US Future Universe</a> subscription. The following table shows the price ($/year) to download the historical data of each resolution for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-futures-bulk-download.html"); ?>

<p>After the first bulk subscription ends, subscribe to the updates to keep your local data current. The following table shows the price ($/year) of the updates of each resolution for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-futures-bulk-updates.html"); ?>

<p>The following table shows the annual price ($/year) of the US Future Universe historical and updates subscriptions for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-futures-universe.html"); ?>

<p>The following table shows the total cost of downloading the required datasets in bulk at minute resolution on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates, shown in the preceding tables.</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-futures-bulk-total.html"); ?>

<h4>Download by Ticker</h4>
<p>The file format of the US Future Universe dataset is one file per Future per day and each file costs 100 QCC = $1 USD.</p>

<p>
    The US Futures dataset is available in several resolutions. 
    The resolution you need depends on the US Future subscriptions you create in your algorithm and the resolution of data you get in <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests'>history requests</a>. 
    The following table describes the file format and costs of each resolution:
</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-futures-per-file.html"); ?>

<p>For example, the following algorithm subscribes to minute resolution data for a universe of ES Futures contracts and creates a continuous contract:</p>

<div class="section-example-container">
<pre class="csharp">public class USFuturesDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2021, 1, 1);
        var future = AddFuture(
            Futures.Indices.SP500EMini,
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            dataMappingMode: DataMappingMode.OpenInterest,
            contractDepthOffset: 0
        );
        future.SetFilter(0, 90);
    }
}</pre>
<pre class="python">class USFuturesDataAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2021, 1, 1)
        future = self.add_future(
            Futures.Indices.SP_500_E_MINI,
            data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
            data_mapping_mode=DataMappingMode.OPEN_INTEREST,
            contract_depth_offset=0
        )
        future.set_filter(0, 90)</pre>
</div>

<p>The following table shows the data cost of the preceding algorithm on the Quant Researcher tier:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Dataset</th>
            <th>Package</th>
            <th>Initial Cost</th>
            <th>Ongoing Cost</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>US Futures Security Master</td>
            <td>Download On Premise</td>
            <td>$600 USD</td>
            <td>$600 USD/year</td>
        </tr>
        <tr>
            <td>US Future Universe</td>
            <td>Download On Premise</td>
            <td>1 ticker over 252 trading days
                <br>=> 1 * 252 files
                <br>= 252 files
                <br>
                <br>252 files @ 100 QCC/file
                <br>=> 25,200 QCC
                <br>= $252 USD
            </td>
            <td>1 ticker
                <br>=> 1 file/day
                <br>
                <br>1 file/day @ 100 QCC/file
                <br>=> 100 QCC/day
                <br>= $1 USD/day
            </td>
        </tr>
        <tr>
            <td>US Futures</td>
            <td>Minute Download</td>
            <td>1 ticker over 252 trading days with 3 data formats
                <br>=&gt; 1 * 252 * 3 files
                <br>=  756 files
                <br>
                <br>756 files @ 50 QCC/file
                <br>=&gt; 756 * 50 QCC
                <br>= 37,800 QCC
                <br>= $378 USD
            </td>
            <td>1 ticker with 3 data formats
                <br>=&gt; 3 files/day
                <br>
                <br>3 file/day @ 50 QCC/file
                <br>=&gt; 150 QCC/day
                <br>= $1.50 USD/day
            </td>
        </tr>
    </tbody>
</table>

<p>The preceding table assumes you download trade, quote, and open interest data. However, you can run backtests with only trade data.</p>
