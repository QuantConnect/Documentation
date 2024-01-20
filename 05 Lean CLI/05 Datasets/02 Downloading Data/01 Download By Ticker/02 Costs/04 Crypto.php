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
            UniverseSettings.Asynchronous = true;            
            AddUniverse(new CryptoCoarseFundamentalUniverse(Market.Coinbase, UniverseSettings, 
                cryptoCoarse =&gt; cryptoCoarse.OrderByDescending(cf =&gt; cf.VolumeInUsd).Take(100).Select(x =&gt; x.Symbol))
            );
        }
    }
}</pre>
<pre class="python">class CryptoDataAlgorithm(QCAlgorithm):
    def Initialize(self) -&gt; None:
        self.SetStartDate(2020, 1, 1)
        self.SetEndDate(2021, 1, 1)
        self.UniverseSettings.Asynchronous = True
        self.AddUniverse(CryptoCoarseFundamentalUniverse(Market.Coinbase, self.UniverseSettings, self.universe_filter))

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

<p>The preceding table assumes you download trade and quote data, but you can run backtests with only trade data.</p>