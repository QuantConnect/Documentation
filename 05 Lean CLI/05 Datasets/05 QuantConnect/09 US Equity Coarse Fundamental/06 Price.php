<p>The US Equity Coarse Universe dataset selects US Equities, so you also need the <a href="/docs/v2/lean-cli/datasets/quantconnect/us-equity#06-Price">US Equities</a> data to use the selected securities. Review the US Equity costs in addition to the prices below.</p>

<h4>Download in Bulk</h4>
<p>To download the US Equity Coarse Universe dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The bulk download also requires the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a> subscription. The first bulk subscription downloads the full historical dataset for one year. After that subscription ends, renew with the cheaper updates subscription to keep your data current. The following table shows the annual price ($/year) of each subscription for every organization tier:</p>

<table class="qc-table table" id='us-equity-coarse-bulk-price'>
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
            <td>600</td>
            <td>240</td>
        </tr>
        <tr>
            <td>Team</td>
            <td>900</td>
            <td>360</td>
        </tr>
        <tr>
            <td>Trading Firm</td>
            <td>1,200</td>
            <td>600</td>
        </tr>
        <tr>
            <td>Institution</td>
            <td>1,800</td>
            <td>960</td>
        </tr>
    </tbody>
</table>

<style>
#us-equity-coarse-bulk-price td:not(:first-child),
#us-equity-coarse-bulk-price th:not(:first-child) {
    text-align: right;
}
</style>

<p>The following table shows the total cost of downloading the required datasets in bulk on the <b>Quant Researcher</b> tier. Other organization tiers apply their own rates, shown in the preceding tables.</p>

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
            <td>US Equity Coarse Universe</td>
            <td>Subscription</td>
            <td>$600</td>
            <td>$240/year</td>
        </tr>
        <tr>
            <td>Total</td>
            <td></td>
            <td>$1,200</td>
            <td>$840/year</td>
        </tr>
    </tbody>
</table>

<h4>Download by Date</h4>
<p>When you download by date, the US Equity Coarse Universe data is one file per day and each file costs 5 QCC = $0.05 USD. The following table shows the cost of downloading one year of data by date on the <b>Quant Researcher</b> tier:</p>

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
            <td>US Equity Coarse Universe</td>
            <td>Download</td>
            <td>252 trading days
                <br>=&gt; 252 files
                <br>
                <br>252 files @ 5 QCC/file
                <br>=&gt; 252 * 5 QCC
                <br>= 1,260 QCC
                <br>= $12.60 USD
            </td>
            <td>1 trading day
                <br>=&gt; 1 file
                <br>
                <br>1 file/day @ 5 QCC/file
                <br>=&gt; 5 QCC/day
                <br>= $0.05 USD/day
            </td>
        </tr>
    </tbody>
</table>
