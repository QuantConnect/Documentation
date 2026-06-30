<h4>Download in Bulk</h4>
<p>To download the US Equities dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price depends on your organization tier and the resolution you need. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a> subscription. The following table shows the price ($/year) to download the historical data of each resolution for each organization tier:</p>

<table class="qc-table table" id='us-equities-bulk-download-price'>
    <thead>
        <tr>
            <th>Resolution</th>
            <th>Quant Researcher</th>
            <th>Team</th>
            <th>Trading Firm</th>
            <th>Institution</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tick</td>
            <td>16,800</td>
            <td>28,800</td>
            <td>33,600</td>
            <td>52,800</td>
        </tr>
        <tr>
            <td>Second</td>
            <td>15,360</td>
            <td>28,800</td>
            <td>33,600</td>
            <td>48,000</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>11,760</td>
            <td>16,800</td>
            <td>31,200</td>
            <td>43,200</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>2,136</td>
            <td>3,480</td>
            <td>3,480</td>
            <td>3,480</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>2,136</td>
            <td>3,480</td>
            <td>3,480</td>
            <td>3,480</td>
        </tr>
    </tbody>
</table>

<p>After the first bulk subscription ends, subscribe to the updates to keep your local data current. The updates cost the same for all resolutions. The following table shows the price ($/year) of the updates for each organization tier:</p>

<table class="qc-table table" id='us-equities-bulk-update-price'>
    <thead>
        <tr>
            <th>Tier</th>
            <th>Price ($/Year)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Quant Researcher</td>
            <td>600</td>
        </tr>
        <tr>
            <td>Team</td>
            <td>840</td>
        </tr>
        <tr>
            <td>Trading Firm</td>
            <td>1,440</td>
        </tr>
        <tr>
            <td>Institution</td>
            <td>2,640</td>
        </tr>
    </tbody>
</table>

<style>
#us-equities-bulk-download-price td:not(:first-child),
#us-equities-bulk-download-price th:not(:first-child),
#us-equities-bulk-update-price td:not(:first-child),
#us-equities-bulk-update-price th:not(:first-child) {
    text-align: right;
}
</style>

<h4>Download by Ticker</h4>
<p>The following table shows the cost of an annual subscription to the US Equity Security Master for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/us-equity-security-master-price.html"); ?>

<p>The US Equities dataset is available is several resolutions. The resolution you need depends on the US Equity subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Resolution</th>
            <th>File Format</th>
            <th>Cost per file</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tick</td>
            <td>One file per security per trading day per data format. Quote and trade data are separate files.</td>
            <td>6 QCC = $0.06 USD</td>
        </tr>
        <tr>
            <td>Second</td>
            <td>One file per security per trading day per data format. Quote and trade data are separate files.</td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>One file per security per trading day per data format. Quote and trade data are separate files.</td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per security.</td>
            <td>300 QCC = $3 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per security.</td>
            <td>100 QCC = $1 USD</td>
        </tr>
    </tbody>
</table>

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
