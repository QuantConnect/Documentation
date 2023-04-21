<p>US Equity algorithms require the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a> and some data from the <a href="https://www.quantconnect.com/datasets/algoseek-us-equities/pricing">US Equities</a> dataset. The following table shows the cost of an annual subscription to the US Equity Security Master for each organization tier:</p>

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

<p>If you add universes to your algorithm, the following table shows the additional datasets you need:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Universe Type</th>
            <th>Required Dataset</th>
            <th>File Format</th>
            <th>Cost per file</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity#02-Coarse-Universe-Selection">Coarse</a> or <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity#03-Dollar-Volume-Selection">Dollar Volume</a></td>
            <td><a href="https://www.quantconnect.com/datasets/quantconnect-us-coarse-universe-constituents/pricing">US Coarse Universe</a></td>
            <td>One file per day.</td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td><a href="https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity#04-ETF-Constituents-Selection">ETF Constituents</a></td>
            <td><a href="https://www.quantconnect.com/datasets/quantconnect-us-etf-constituents/pricing">US ETF Constituents</a></td>
            <td>One file per ETF per day.</td>
            <td>50 QCC = $0.50 USD</td>
        </tr>
    </tbody>
</table>

<p>For example, the following algorithm creates a dollar volume universe with 100 securities and then subscribes to minute resolution data for each US Equity in the universe:</p>

<div class="section-example-container">
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class USEquityDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);
            AddUniverse(Universe.DollarVolume.Top(100));
        }
    }
}</pre>
<pre class="python">class USEquityDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        self.AddUniverse(self.Universe.DollarVolume.Top(100))</pre>
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
            <td>US Coarse Universe</td>
            <td>On Premise Download</td>
            <td>252 trading days 
                    <br>=&gt; 252 files <br><br>252 files @ 5 QCC/file<br>=&gt; 252 * 5 QCC<br> = 12,600 QCC <br>= $126 USD</td>
            <td>1 trading day<br>=&gt; 1 file<br><br>1 file/day @ 5 QCC/file<br>=&gt; 5 QCC/day<br>= $0.05 USD/day</td>
        </tr>
        <tr>
            <td>US Equity</td>
            <td>Minute Download</td>
            <td>100 securities over 252 trading days with 2 data formats<br>=&gt; 100 * 252 * 2 files<br>= 50,400 files<br><br>50,400 files @ 5 QCC/file<br>=&gt; 50,400 * 5 QCC <br>= 252,000 QCC <br>= $2,520 USD</td>
            <td>100 securities with 2 data formats<br>=&gt; 100 * 2 files/day<br>= 200 files/day<br><br>200 files/day @ 5 QCC/file<br>=&gt; 200 * 5 QCC/day<br>= 1,000 QCC/day<br>= $10 USD/day</td>
        </tr>
    </tbody>
</table>
