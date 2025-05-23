<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-fundamentals'>fundamental data</a>, call the <code>History&lt;Fundamental&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-fundamentals'>fundamental data</a>, call the <code>history</code> method with the <code>Fundamental</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the <a href='/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data#99-Data-Point-Attributes'>data point attributes</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityFundamentalHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 27);
        // Get the Symbol of an asset.
        var symbol = AddEquity("AAPL").Symbol;
        // Get the 3 trailing daily Fundamental objects of the asset. 
        var history = History&lt;Fundamental&gt;(symbol, 3, Resolution.Daily);
        // Iterate through each Fundamental object.
        foreach (var fundamental in history)
        {
            var t = fundamental.EndTime;
            var currentRatio = fundamental.OperationRatios.CurrentRatio.Value;
        }
    }
}</pre>
    <pre class="python">class USEquityFundamentalHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 27)
        # Get the Symbol of an asset.
        symbol = self.add_equity('AAPL').symbol
        # Get the 3 trailing daily Fundamental objects of the asset in DataFrame format. 
        history = self.history(Fundamental, symbol, 3, Resolution.DAILY)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>adjustedprice</th>
        <th>assetclassification</th>
        <th>companyprofile</th>
        <th>companyreference</th>
        <th>dollarvolume</th>
        <th>earningratios</th>
        <th>earningreports</th>
        <th>financialstatements</th>
        <th>hasfundamentaldata</th>
        <th>market</th>
        <th>marketcap</th>
        <th>operationratios</th>
        <th>pricefactor</th>
        <th>pricescalefactor</th>
        <th>securityreference</th>
        <th>splitfactor</th>
        <th>valuationratios</th>
        <th>value</th>
        <th>volume</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="3" valign="top">AAPL</th>
        <th>2024-12-17</th>
        <td>250.764283</td>
        <td>QuantConnect.Data.Fundamental.AssetClassification</td>
        <td>QuantConnect.Data.Fundamental.CompanyProfile</td>
        <td>QuantConnect.Data.Fundamental.CompanyReference</td>
        <td>1.140352e+10</td>
        <td>QuantConnect.Data.Fundamental.EarningRatios</td>
        <td>QuantConnect.Data.Fundamental.EarningReports</td>
        <td>QuantConnect.Data.Fundamental.FinancialStatements</td>
        <td>True</td>
        <td>usa</td>
        <td>3587438272590</td>
        <td>QuantConnect.Data.Fundamental.OperationRatios</td>
        <td>0.998902</td>
        <td>0.998902</td>
        <td>QuantConnect.Data.Fundamental.SecurityReference</td>
        <td>1.0</td>
        <td>QuantConnect.Data.Fundamental.ValuationRatios</td>
        <td>251.04</td>
        <td>45425121</td>
      </tr>
      <tr>
        <th>2024-12-18</th>
        <td>253.201603</td>
        <td>QuantConnect.Data.Fundamental.AssetClassification</td>
        <td>QuantConnect.Data.Fundamental.CompanyProfile</td>
        <td>QuantConnect.Data.Fundamental.CompanyReference</td>
        <td>1.196673e+10</td>
        <td>QuantConnect.Data.Fundamental.EarningRatios</td>
        <td>QuantConnect.Data.Fundamental.EarningReports</td>
        <td>QuantConnect.Data.Fundamental.FinancialStatements</td>
        <td>True</td>
        <td>usa</td>
        <td>3587438272590</td>
        <td>QuantConnect.Data.Fundamental.OperationRatios</td>
        <td>0.998902</td>
        <td>0.998902</td>
        <td>QuantConnect.Data.Fundamental.SecurityReference</td>
        <td>1.0</td>
        <td>QuantConnect.Data.Fundamental.ValuationRatios</td>
        <td>253.48</td>
        <td>47209742</td>
      </tr>
      <tr>
        <th>2024-12-19</th>
        <td>247.777567</td>
        <td>QuantConnect.Data.Fundamental.AssetClassification</td>
        <td>QuantConnect.Data.Fundamental.CompanyProfile</td>
        <td>QuantConnect.Data.Fundamental.CompanyReference</td>
        <td>1.284373e+10</td>
        <td>QuantConnect.Data.Fundamental.EarningRatios</td>
        <td>QuantConnect.Data.Fundamental.EarningReports</td>
        <td>QuantConnect.Data.Fundamental.FinancialStatements</td>
        <td>True</td>
        <td>usa</td>
        <td>3587438272590</td>
        <td>QuantConnect.Data.Fundamental.OperationRatios</td>
        <td>0.998902</td>
        <td>0.998902</td>
        <td>QuantConnect.Data.Fundamental.SecurityReference</td>
        <td>1.0</td>
        <td>QuantConnect.Data.Fundamental.ValuationRatios</td>
        <td>248.05</td>
        <td>51778802</td>
      </tr>
    </tbody>
  </table>
</div>


<div class="python section-example-container">
    <pre class="python"># Get the Current Ratio of each row.
current_ratios = history.apply(lambda row: row.operationratios.current_ratio.value, axis=1)</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time      
AAPL    2024-12-24    0.867313
        2024-12-25    0.867313
        2024-12-27    0.867313
dtype: float64</pre>
</div>

<p class='python'>
  To avoid consuming unnecessary computational resources populating the DataFrame, you can instead request <code>Fundamental</code> objects. 
  To get a list of <code>Fundamental</code> objects, call the <code>history[Fundamental]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 3 trailing daily Fundamental objects of an asset in Fundamental format. 
history = self.history[Fundamental](symbol, 3, Resolution.DAILY)
# Iterate through each Fundamental object and access its properites.
for fundamental in history:
    symbol = fundamental.symbol
    pe_ratio = fundamental.valuation_ratios.pe_ratio</pre>
</div>
