<p>Both the bulk and by-ticker downloads require the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a>. The following table shows the cost of an annual subscription to the US Equity Security Master for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/us-equity-security-master-price.html"); ?>

<h4>Download in Bulk</h4>
<p>To download the US Equities dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price depends on your organization tier and the resolution you need. The following table shows the price ($/year) to download the historical data of each resolution for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-equity-bulk-download.html"); ?>

<p>After the first bulk subscription ends, subscribe to the updates to keep your local data current. The updates cost the same for all resolutions. The following table shows the price ($/year) of the updates for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-equity-bulk-updates.html"); ?>

<p>The following table shows the total cost of downloading the required datasets in bulk at minute resolution on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates, shown in the preceding tables.</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-equity-bulk-total.html"); ?>

<h4>Download by Ticker</h4>
<p>The US Equities dataset is available is several resolutions. The resolution you need depends on the US Equity subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

<?php include(DOCS_RESOURCES."/datasets/lean-cli-prices/us-equity-per-file.html"); ?>

<p>For example, the following algorithm subscribes to minute resolution data for a US Equity:</p>

<div class="section-example-container">
<pre class="csharp">public class USEquityDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2021, 1, 1);
        AddEquity("SPY", Resolution.Minute);
    }
}</pre>
<pre class="python">class USEquityDataAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2021, 1, 1)
        self.add_equity("SPY", Resolution.MINUTE)</pre>
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
            <td>US Equity Security Master</td>
            <td>Download On Premise</td>
            <td>$600 USD</td>
            <td>$600 USD/year</td>
        </tr>
        <tr>
            <td>US Equity</td>
            <td>Minute Download</td>
            <td>1 security over 252 trading days with 2 data formats<br>=&gt; 1 * 252 * 2 files<br>= 504 files<br><br>504 files @ 5 QCC/file<br>=&gt; 504 * 5 QCC <br>= 2,520 QCC <br>= $25.20 USD</td>
            <td>1 security with 2 data formats<br>=&gt; 1 * 2 files/day<br>= 2 files/day<br><br>2 files/day @ 5 QCC/file<br>=&gt; 2 * 5 QCC/day<br>= 10 QCC/day<br>= $0.10 USD/day</td>
        </tr>
    </tbody>
</table>

<p>The preceding table assumes you download trade and quote data, but you can run backtests with only trade data.</p>
