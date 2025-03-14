<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FutureChainProvider.GetFutureContractList(future.Symbol, Time)
            .OrderBy(symbol => symbol.ID.Date).First();";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        contract_symbols = self.future_chain_provider.get_future_contract_list(future.symbol, self.time)
        symbol = sorted(contract_symbols, key=lambda symbol: symbol.id.date)[0]";
$assetClass = "Future";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#03-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>asksize</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
      <th>bidsize</th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
    </tr>
    <tr>
      <th>expiry</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20 13:30:00</th>
      <th rowspan='5' valign='top'>ES YOGVNNAOI1OH</th>
      <th>2024-12-12 17:00:00</th>
      <td>6058.75</td>
      <td>6087.75</td>
      <td>6055.75</td>
      <td>6082.50</td>
      <td>19.0</td>
      <td>6058.50</td>
      <td>6087.50</td>
      <td>6055.50</td>
      <td>6082.25</td>
      <td>52.0</td>
      <td>6058.625</td>
      <td>6087.625</td>
      <td>6055.625</td>
      <td>6082.375</td>
    </tr>
    <tr>
      <th>2024-12-13 17:00:00</th>
      <td>6051.50</td>
      <td>6085.00</td>
      <td>6041.50</td>
      <td>6077.50</td>
      <td>59.0</td>
      <td>6051.25</td>
      <td>6084.75</td>
      <td>6041.25</td>
      <td>6077.25</td>
      <td>1.0</td>
      <td>6051.375</td>
      <td>6084.875</td>
      <td>6041.375</td>
      <td>6077.375</td>
    </tr>
    <tr>
      <th>2024-12-16 17:00:00</th>
      <td>6076.75</td>
      <td>6090.75</td>
      <td>6064.75</td>
      <td>6072.50</td>
      <td>10.0</td>
      <td>6076.50</td>
      <td>6090.50</td>
      <td>6064.50</td>
      <td>6072.25</td>
      <td>7.0</td>
      <td>6076.625</td>
      <td>6090.625</td>
      <td>6064.625</td>
      <td>6072.375</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>6052.25</td>
      <td>6063.50</td>
      <td>6041.00</td>
      <td>6053.00</td>
      <td>12.0</td>
      <td>6052.00</td>
      <td>6063.25</td>
      <td>6040.50</td>
      <td>6052.75</td>
      <td>1.0</td>
      <td>6052.125</td>
      <td>6063.375</td>
      <td>6040.750</td>
      <td>6052.875</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>5872.75</td>
      <td>6074.75</td>
      <td>5840.25</td>
      <td>6050.25</td>
      <td>2.0</td>
      <td>5872.25</td>
      <td>6074.50</td>
      <td>5840.00</td>
      <td>6050.00</td>
      <td>1.0</td>
      <td>5872.500</td>
      <td>6074.625</td>
      <td>5840.125</td>
      <td>6050.125</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry               symbol           time               
2024-12-20 13:30:00  ES YOGVNNAOI1OH  2024-12-12 17:00:00    0.25
                                      2024-12-13 17:00:00    0.25
                                      2024-12-16 17:00:00    0.25
                                      2024-12-17 17:00:00    0.25
                                      2024-12-18 17:00:00    0.50
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>
