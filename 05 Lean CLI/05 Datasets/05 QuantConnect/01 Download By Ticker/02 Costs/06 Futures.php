<p>Futures algorithms require some data from the <a href='https://www.quantconnect.com/datasets/algoseek-us-futures'>US Futures</a> dataset. The US Futures dataset is available in several resolutions. The resolution you need depends on the US Future subscriptions you create in your algorithm and the resolution of data you get in <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/history-requests'>history requests</a>. The following table describes the file format and costs of each resolution:</p>

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
            <td>One file per ticker per trading day per data format. Trade, quote, and open interest data are separate files.</td>
            <td>6 QCC = $0.06 USD</td>
        </tr>
        <tr>
            <td>Second</td>
            <td>One file per ticker per trading day per data format. Trade, quote, and open interest data are separate files.</td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td>Minute</td>
            <td>One file per ticker per trading day per data format. Trade, quote, and open interest data are separate files.</td>
            <td>5 QCC = $0.05 USD</td>
        </tr>
        <tr>
            <td>Hour</td>
            <td>One file per ticker per data format. Trade, quote, and open interest data are separate files.</td>
            <td>300 QCC = $3 USD</td>
        </tr>
        <tr>
            <td>Daily</td>
            <td>One file per ticker per data format. Trade, quote, and open interest data are separate files.</td>
            <td>100 QCC = $1 USD</td>
        </tr>
    </tbody>
</table>

<p>If you want <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous contracts</a> in your algorithm, you also need the <a href='https://www.quantconnect.com/datasets/quantconnect-us-futures-security-master'>US Futures Security Master</a> dataset. The following table shows the cost of an annual subscription to the US Futures Security Master for each organization tier:</p>

<?php include(DOCS_RESOURCES."/datasets/us-equity-security-master-price.html"); ?>

<p>For example, the following algorithm subscribes to minute resolution data for a universe of ES Futures contracts and creates a continuous contract:</p>

<div class="section-example-container">
<pre class="csharp">namespace QuantConnect.Algorithm.CSharp
{
    public class USFuturesDataAlgorithm : QCAlgorithm
    {
        public override void Initialize()
        {
            SetStartDate(2020, 1, 1);
            SetEndDate(2021, 1, 1);
            var future = AddFuture(Futures.Indices.SP500EMini,
                dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
                dataMappingMode: DataMappingMode.OpenInterest,
                contractDepthOffset: 0
            );
            future.SetFilter(0, 90);
        }
    }
}
</pre>
<pre class="python">class USFuturesDataAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2021, 1, 1)
        future = self.add_future(Futures.indices.SP500EMini,
                                dataNormalizationMode = DataNormalizationMode.BACKWARDS_RATIO,
                                dataMappingMode = DataMappingMode.OPEN_INTEREST,
                                contractDepthOffset = 0)
        future.set_filter(0, 90)</pre>
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
            <td>US Futures Security Master</td>
            <td>Download On Premise</td>
            <td>$600 USD</td>
            <td>$0 USD/year</td>
        </tr>
        <tr>
            <td>US Futures</td>
            <td>Minute Download</td>
            <td>1 ticker over 252 trading days with 3 data formats
                <br>=&gt; 1 * 252 * 3 files
                <br>=  756 files
                <br>
                <br>756 files @ 5 QCC/file
                <br>=&gt; 756 * 5 QCC
                <br>= 3,780 QCC 
                <br>= $37.80 USD
            </td>
            <td>1 ticker with 3 data formats
                <br>=&gt; 3 files/day
                <br>
                <br>3 file/day @ 5 QCC/file
                <br>=&gt; 15 QCC/day
                <br>= $0.15 USD/day
            </td>
        </tr>
    </tbody>
</table>

<p>The preceding table assumes you download trade, quote, and open interest data. However, you can run backtests with only trade data.</p>