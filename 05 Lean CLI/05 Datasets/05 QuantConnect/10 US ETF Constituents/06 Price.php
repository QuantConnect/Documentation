<p>The US ETF Constituents dataset selects US Equities, so you also need the <a href="/docs/v2/lean-cli/datasets/quantconnect/us-equity#06-Price">US Equities</a> data to use the selected constituents. Review the US Equity costs in addition to the prices below.</p>

<h4>Download in Bulk</h4>
<p>To download the US ETF Constituents dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a> subscription. The first bulk subscription downloads the full historical dataset for one year. After that subscription ends, renew with the cheaper updates subscription to keep your data current. The following table shows the annual price ($/year) of each subscription for every organization tier:</p>

<table class="qc-table table" id='us-etf-constituents-bulk-price'>
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
            <td>3,600</td>
            <td>1,200</td>
        </tr>
        <tr>
            <td>Team</td>
            <td>3,960</td>
            <td>1,200</td>
        </tr>
        <tr>
            <td>Trading Firm</td>
            <td>3,960</td>
            <td>1,200</td>
        </tr>
        <tr>
            <td>Institution</td>
            <td>3,960</td>
            <td>1,200</td>
        </tr>
    </tbody>
</table>

<style>
#us-etf-constituents-bulk-price td:not(:first-child),
#us-etf-constituents-bulk-price th:not(:first-child) {
    text-align: right;
}
</style>

<p>The following table shows the total cost of downloading the required datasets, including minute US Equities data for the selected constituents, in bulk on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates.</p>

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
            <td>US Equity Security Master</td>
            <td>Subscription</td>
            <td>$600</td>
            <td>$600/year</td>
        </tr>
        <tr>
            <td>US ETF Constituents</td>
            <td>Subscription</td>
            <td>$3,600</td>
            <td>$1,200/year</td>
        </tr>
        <tr>
            <td>US Equities</td>
            <td>Minute</td>
            <td>$11,760</td>
            <td>$600/year</td>
        </tr>
        <tr>
            <td>Total</td>
            <td></td>
            <td>$15,960</td>
            <td>$2,400/year</td>
        </tr>
    </tbody>
</table>

<h4>Download by Date</h4>
<p>When you download by date, the US ETF Constituents data is one file per ETF per day and each file costs 50 QCC = $0.50 USD. The following table shows the cost of downloading one year of data for one ETF by date on the <b>Quant Researcher</b> tier, assuming you download minute US Equities data for all 500 constituents of an ETF such as SPY:</p>

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
            <td>Subscription</td>
            <td>$600</td>
            <td>$600/year</td>
        </tr>
        <tr>
            <td>US ETF Constituents</td>
            <td>Download</td>
            <td>1 ETF over 252 trading days
                <br>=&gt; 252 files
                <br>
                <br>252 files @ 50 QCC/file
                <br>=&gt; 252 * 50 QCC
                <br>= 12,600 QCC
                <br>= $126 USD
            </td>
            <td>1 ETF
                <br>=&gt; 1 file/day
                <br>
                <br>1 file/day @ 50 QCC/file
                <br>=&gt; 50 QCC/day
                <br>= $0.50 USD/day
            </td>
        </tr>
        <tr>
            <td>US Equities</td>
            <td>Minute</td>
            <td>500 constituents over 252 trading days with 2 data formats
                <br>=&gt; 500 * 252 * 2 files
                <br>= 252,000 files
                <br>
                <br>252,000 files @ 5 QCC/file
                <br>=&gt; 252,000 * 5 QCC
                <br>= 1,260,000 QCC
                <br>= $12,600 USD
            </td>
            <td>500 constituents with 2 data formats
                <br>=&gt; 1,000 files/day
                <br>
                <br>1,000 files/day @ 5 QCC/file
                <br>=&gt; 5,000 QCC/day
                <br>= $50 USD/day
            </td>
        </tr>
    </tbody>
</table>

<p>This example downloads all 500 constituents of an ETF such as SPY to show the maximum cost. The cost depends on your selection, so you can download fewer constituents (for example, 50) to reduce it. As the number of constituents grows, compare the by-date US Equities cost with the bulk cost above to determine when downloading in bulk is cheaper.</p>
