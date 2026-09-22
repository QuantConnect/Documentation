<h4>Download in Bulk</h4>
<p>To download the US Index Options dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price depends on your organization tier and the resolution you need. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-index-option-universe">US Index Option Universe</a> subscription. The following table shows the price ($/year) to download the historical data of each resolution for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-index-options-bulk-download.html"); ?>

<p>After the first bulk subscription ends, subscribe to the updates to keep your local data current. The following table shows the price ($/year) of the updates of each resolution for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-index-options-bulk-updates.html"); ?>

<p>The following table shows the annual price ($/year) of the US Index Option Universe historical and updates subscriptions for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-index-options-universe.html"); ?>

<p>The following table shows the total cost of downloading the required datasets in bulk at minute resolution on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates, shown in the preceding tables.</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-index-options-bulk-total.html"); ?>

<h4>Download by Ticker</h4>
<p>
    The file format of the US Index Option Universe data is one file per underlying Index and each file costs 100 QCC = $1 USD.
    The US Index Options dataset is available in several resolutions. 
    The resolution you need depends on the US Index Option subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. 
    The following table describes the file format and costs of each resolution:
</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-index-options-per-file.html"); ?>

<p>For example, the following algorithm subscribes to minute resolution data for a universe of SPXW Index Option contracts:</p>

<div class="section-example-container">
<pre class="csharp">public class USIndexOptionsDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2021, 1, 1);
        AddIndexOption("SPX", "SPXW");
    }
}</pre>
<pre class="python">class USIndexOptionsDataAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2021, 1, 1)
        self.add_index_option("SPX", "SPXW")</pre>
</div>

<p>The following table shows the data cost of the preceding algorithm:</p>

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
            <td>US Index Option Universe</td>
            <td>Download On Premise</td>
            <td>
                1 underlying Index over 252 trading days
                <br>=&gt; 1 * 252 files
                <br>= 252 files
                <br>
                <br>252 files @ 100 QCC/file
                <br>=&gt; 252 * 100 QCC
                <br>= 25,200 QCC
                <br>= $252 USD
            </td>
            <td>
                1 underlying Index
                <br>=&gt; 1 file/day
                <br>
                <br>1 file/day @ 100 QCC/file
                <br>=&gt; 100 QCC/day
                <br>= $1 USD/day
            </td>
        </tr>
        <tr>
            <td>US Index Options</td>
            <td>Minute Download</td>
            <td>
                1 ticker over 252 trading days with 3 data formats
                <br>=&gt; 1 * 252 * 3 files
                <br>= 756 files
                <br>
                <br>756 files @ 15 QCC/file
                <br>=&gt; 756 * 15 QCC
                <br>= 11,340 QCC 
                <br>= $113.40 USD
            </td>
            <td>1 ticker with 3 data formats
                <br>=&gt; 3 files/day
                <br>
                <br>3 files/day @ 15 QCC/file
                <br>=&gt; 45 QCC/day
                <br>= $0.45 USD/day
            </td>
        </tr>
    </tbody>
</table>

<p>The preceding table assumes you download trade, quote, and open interest data. However, you can run backtests with only trade data.</p>
