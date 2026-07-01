<h4>Download in Bulk</h4>
<p>To download the US Index Options dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price depends on your organization tier and the resolution you need. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-index-option-universe">US Index Option Universe</a> subscription. The following table shows the price ($/year) to download the historical data of each resolution for each organization tier:</p>

<table class="qc-table table" id='us-index-options-bulk-download-price'>
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
            <td>Minute</td>
            <td>30,000</td>
            <td>33,600</td>
            <td>40,800</td>
            <td>64,800</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>14,400</td>
            <td>21,000</td>
            <td>26,400</td>
            <td>34,800</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>12,000</td>
            <td>15,600</td>
            <td>18,000</td>
            <td>21,000</td>
        </tr>
    </tbody>
</table>

<p>After the first bulk subscription ends, subscribe to the updates to keep your local data current. The following table shows the price ($/year) of the updates of each resolution for each organization tier:</p>

<table class="qc-table table" id='us-index-options-bulk-update-price'>
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
            <td>Minute</td>
            <td>1,200</td>
            <td>1,680</td>
            <td>2,160</td>
            <td>2,880</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>1,440</td>
            <td>1,440</td>
            <td>1,920</td>
            <td>2,640</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>720</td>
            <td>960</td>
            <td>1,440</td>
            <td>1,920</td>
        </tr>
    </tbody>
</table>

<p>The following table shows the annual price ($/year) of the US Index Option Universe historical and updates subscriptions for each organization tier:</p>

<table class="qc-table table" id='us-index-option-universe-bulk-price'>
    <thead>
        <tr>
            <th>Tier</th>
            <th>Historical</th>
            <th>Updates</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Quant Researcher</td>
            <td>1,200</td>
            <td>960</td>
        </tr>
        <tr>
            <td>Team</td>
            <td>1,920</td>
            <td>1,200</td>
        </tr>
        <tr>
            <td>Trading Firm</td>
            <td>2,880</td>
            <td>1,440</td>
        </tr>
        <tr>
            <td>Institution</td>
            <td>3,960</td>
            <td>1,680</td>
        </tr>
    </tbody>
</table>

<style>
#us-index-options-bulk-download-price td:not(:first-child),
#us-index-options-bulk-download-price th:not(:first-child),
#us-index-options-bulk-update-price td:not(:first-child),
#us-index-options-bulk-update-price th:not(:first-child),
#us-index-option-universe-bulk-price td:not(:first-child),
#us-index-option-universe-bulk-price th:not(:first-child) {
    text-align: right;
}
</style>

<p>The following table shows the total cost of downloading the required datasets in bulk at minute resolution on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates, shown in the preceding tables.</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Dataset</th>
            <th>Package</th>
            <th>Historical</th>
            <th>Updates</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>US Index Option Universe</td>
            <td>Subscription</td>
            <td>$1,200</td>
            <td>$960/year</td>
        </tr>
        <tr>
            <td>US Index Options</td>
            <td>Minute</td>
            <td>$30,000</td>
            <td>$1,200/year</td>
        </tr>
        <tr>
            <td>Total</td>
            <td></td>
            <td>$31,200</td>
            <td>$2,160/year</td>
        </tr>
    </tbody>
</table>

<h4>Download by Ticker</h4>
<p>
    The file format of the US Index Option Universe data is one file per underlying Index and each file costs 100 QCC = $1 USD.
    The US Index Options dataset is available in several resolutions. 
    The resolution you need depends on the US Index Option subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. 
    The following table describes the file format and costs of each resolution:
</p>

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
            <td>Minute</td>
            <td>One file per ticker per trading day per data format. Trade, quote, and open interest data are separate files.</td>
            <td>15 QCC = $0.15 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per ticker per data format. Trade, quote, and open interest data are separate files.</td>
            <td>900 QCC = $9 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per ticker per data format. Trade, quote, and open interest data are separate files.</td>
            <td>300 QCC = $3 USD</td>
        </tr>
    </tbody>
</table>

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
