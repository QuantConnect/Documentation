<p>The following sections describe how to calculate the approximate cost of downloading local data for algorithms of each asset class. The prices reflect the data prices at the time of writing. To view the current prices of each dataset, open a dataset listing in the <a href="https://www.quantconnect.com/datasets">Dataset Market</a> and then click the <span class="page-section-name">Pricing</span> tab. To download the data, you can use the <a href="/docs/v2/lean-cli/datasets/downloading-data/download-by-ticker#02-Using-the-CLI">lean data download</a> command or the <a href="/docs/v2/lean-cli/backtesting/deployment#04-Download-Datasets-During-Backtests">ApiDataProvider</a>.</p>

<h4>US Equity</h4>
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


<h4>Equity Options</h4>
<p>Equity Option algorithms require the <a href="https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/pricing">US Equity Security Master</a>, some data from the <a href="https://www.quantconnect.com/datasets/algoseek-us-equity-options/pricing">US Equity Options</a> dataset, and data for the underlying US Equity universes and assets. The following table shows the cost of an annual subscription to the US Equity Security Master for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/us-equity-security-master-price.html"); ?>

<p>The US Equity Options dataset is available is several resolutions. The resolution you need depends on the US Equity Option subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
            <td>One file per Option per trading day per data format. Quote, trade, and open interest data are separate files.</td>
            <td>15 QCC = $0.15 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per Option per year per data format. Trade and open interest data are separate files.</td>
            <td>900 QCC = $9 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per Option per year. Trade and open interest data are separate files.</td>
            <td>300 QCC = $3 USD</td>
        </tr>
    </tbody>
</table>

<p>For example, the following algorithm subscribes to minute resolution data for an Equity Option and its underlying asset:<br></p>



<div class="section-example-container">
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class USEquityOptionsDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);
            var underlying = AddEquity("GOOG").Symbol;
            AddOption(underlying);
        }
    }
}</pre>
<pre class="python">class USEquityOptionsDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        underlying = self.AddEquity("GOOG").Symbol
        self.AddOption(underlying)</pre>
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
            <td>1 security with 2 data formats<br>=&gt; 2 files/day<br><br>2 files/day @ 5 QCC/file<br>=&gt; 2 * 5 QCC/day<br>= 10 QCC/day<br>= $0.10 USD/day</td>
        </tr>
        <tr>
            <td>US Equity Options</td>
            <td>Minute Download</td>
            <td>1 Option over 252 trading days with 3 data formats<br>=&gt; 1 * 252 * 3 files<br>= 756 files<br><br>756 files @ 15 QCC/file<br>=&gt; 756 * 15 QCC <br>= 11,360 QCC <br>= $113.60 USD</td>
            <td>1 Option with 3 data formats<br>=&gt; 3 files/day<br><br>3 files/day @ 15 QCC/file<br>=&gt; 3 * 15 QCC/day<br>= 45 QCC/day<br>= $0.45 USD/day</td>
        </tr>
    </tbody>
</table>

<h4>Crypto</h4>
<p>Crypto algorithms require at least one of the <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/datasets/coinapi">CoinAPI datasets</a>. The CoinAPI datasets are available is several resolutions. The resolution you need depends on the Crypto subscriptions you create in your algorithm and the resolution of data you get in&nbsp;<a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
            <td>One file per security per trading day&nbsp;per brokerage per data format. Quote and trade data are separate files.<br></td>
            <td>100 QCC = $1 USD</td>
        </tr>
        <tr>
            <td>Second</td>
            <td>One file per security per trading day&nbsp;per brokerage per data format. Quote and trade data are separate files.<br></td>
            <td>25 QCC = $0.25 USD</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>One file per security per trading day&nbsp;per brokerage per data format. Quote and trade data are separate files.<br></td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per security&nbsp;per brokerage.<br></td>
            <td>400 QCC = $4 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per security&nbsp;per brokerage.<br></td>
            <td>100 QCC = $1 USD</td>
        </tr>
    </tbody>
</table>

<p>If you add universes to your algorithm, you also need <code>CryptoCoarseFundamental</code> data. The file format of <code>CryptoCoarseFundamental</code> data is one file per day per brokerage and each file costs 100 QCC = $1 USD.</p>

<p>For example, the following algorithm creates a universe of 100 Cryptocurrencies and then subscribes to minute resolution data for each one in the universe:</p>

<div class="section-example-container">
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class CryptoDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);            
            AddUniverse(new CryptoCoarseFundamentalUniverse(Market.GDAX, UniverseSettings, 
                cryptoCoarse =&gt; cryptoCoarse.OrderByDescending(cf =&gt; cf.VolumeInUsd).Take(100).Select(x =&gt; x.Symbol))
            );
        }
    }
}</pre>
<pre class="python">class CryptoDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        self.AddUniverse(CryptoCoarseFundamentalUniverse(Market.GDAX, self.UniverseSettings, self.universe_filter))

    def universe_filter(self, crypto_coarse: List[CryptoCoarseFundamental]) -&gt; List[Symbol]:
        sorted_by_dollar_volume = sorted(crypto_coarse, key=lambda cf: cf.VolumeInUsd, reverse=True)
        return [cf.Symbol for cf in sorted_by_dollar_volume[:100]]</pre>
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
            <td>Coinbase Crypto Price Data</td>
            <td>Universe Download</td>
            <td>365 days <br>=&gt; 365 files <br><br>365&nbsp;files @ 100 QCC/file<br>=&gt; 365 * 100 QCC<br> = 36,500 QCC <br>= $365 USD</td>
            <td>1 file per day @ 100 QCC/file<br>=&gt; 100 QCC/day<br>= $1 USD/day</td>
        </tr>
        <tr>
            <td>Coinbase Crypto Price Data</td>
            <td>Minute Download</td>
            <td>100 securities over 365 trading days with 2 data formats<br>=&gt; 1 * 100 * 365 * 2 files<br>= 73,000 files<br><br>73,000&nbsp;files @ 5 QCC/file<br>=&gt; 73,000 * 5 QCC <br>= 365,000 QCC <br>= $3,650 USD</td>
            <td>100 securities with 2 data formats<br>=&gt; 100 * 2 files/day<br>= 200 files/day<br><br>200 files/day @ 5 QCC/file<br>=&gt; 200 * 5 QCC/day<br>= 1,000 QCC/day<br>= $10 USD/day</td>
        </tr>
    </tbody>
</table>


<h4>Forex</h4>
<p>Forex algorithms require some data from the <a href="https://www.quantconnect.com/datasets/oanda-forex/pricing">FOREX</a> dataset. The FOREX dataset is available is several resolutions. The resolution you need depends on the Forex subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
            <td>One file per currency pair per trading day.</td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>One file per&nbsp;currency&nbsp;pair&nbsp;per trading day.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per&nbsp;currency&nbsp;pair.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per&nbsp;currency&nbsp;pair.<br></td>
            <td>3 QCC = $0.03 USD</td>
        </tr>
    </tbody>
</table>

<p>For example, the following algorithm subscribes to minute resolution data for one Forex pair:</p>

<div class="section-example-container">
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class ForexDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);
            AddForex("USDCAD");
        }
    }
}</pre>
<pre class="python">class ForexDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        self.AddForex("USDCAD")</pre>
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
            <td>FOREX Data</td>
            <td>Minute Download</td>
            <td>1 currency pair over 312 trading days
                <br>=&gt; 312 files<br>
                <br>312 files @ 3 QCC/file
                <br>=&gt; 312 * 3 QCC
                <br>= 936 QCC 
                <br>= $9.36 USD
            </td>
            <td>1 currency pair/day<br>=&gt;&nbsp;1 file/day<br>
                <br>1 file/day @ 3 QCC/file
                <br>=&gt; 3 QCC/day<br>= $0.03 USD/day
            </td>
        </tr>
    </tbody>
</table>


<h4>CFD</h4>
<p>CFD algorithms require some data from the <a href="https://www.quantconnect.com/datasets/oanda-cfd-data/pricing">CFD</a> dataset. The CFD dataset is available is several resolutions. The resolution you need depends on the CFD subscriptions you create in your algorithm and the resolution of data you get in <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class CFDDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);
            AddCfd("XAUUSD");
        }
    }
}</pre>
<pre class="python">class CFDDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        self.AddCfd("XAUUSD")</pre>
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


<h4>Alternative Data</h4>
<p>Algorithms that use alternative data require some data from the associated alternative dataset. To view the cost of each alternative dataset, open a dataset listing in the <a href='https://www.quantconnect.com/datasets'>Dataset Market</a> and then click the <span class='tab-name'>Pricing</span> tab.</p>
