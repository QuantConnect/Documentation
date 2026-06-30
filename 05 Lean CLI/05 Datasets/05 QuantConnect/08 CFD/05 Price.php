<h4>Download in Bulk</h4>
<p>To download the CFD dataset in bulk, subscribe to it on the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization. The price is the same for all resolutions and organization tiers: $800/year to download the historical data and $200/year for the daily updates.</p>

<h4>Download by Ticker</h4>
<p>The CFD dataset is available is several resolutions. The resolution you need depends on the CFD subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
            <td>Second</td>
            <td>One file per contract per trading day.</td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>One file per contract&nbsp;per trading day.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per contract.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per contract.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
    </tbody>
</table>

<p>For example, the following algorithm subscribes to minute resolution data for one CFD contract:</p>

<div class="section-example-container">
<pre class="csharp">public class CFDDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2021, 1, 1);
        AddCfd("XAUUSD");
    }
}</pre>
<pre class="python">class CFDDataAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2021, 1, 1)
        self.add_cfd("XAUUSD")</pre>
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
            <td>CFD Data</td>
            <td>Minute Download
            </td><td>1 contract over 314 trading days
                <br>=&gt; 314 files
                <br>
                <br>314 files @ 3 QCC/file
                <br>=&gt; 314 * 3 QCC
                <br>= 942 QCC 
                <br>= $9.42 USD
            </td>
            <td>1 contract/day<br>=&gt;&nbsp;1 file/day<br>
                <br>1 file/day @ 3 QCC/file
                <br>=&gt; 3 QCC/day
                <br>= $0.03 USD/day
            </td>
        </tr>
    </tbody>
</table>
