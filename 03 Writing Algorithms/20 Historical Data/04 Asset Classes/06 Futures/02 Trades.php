<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FutureChainProvider.GetFutureContractList(future.Symbol, Time)
            .OrderBy(symbol => symbol.ID.Date).First();";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        contract_symbols = self.future_chain_provider.get_future_contract_list(future.symbol, self.time)
        symbol = sorted(contract_symbols, key=lambda symbol: symbol.id.date)[0]";
$assetClass = "Future";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#02-Trades";
$dataType = "TradeBar";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20 13:30:00</th>
      <th rowspan='5' valign='top'>ES YOGVNNAOI1OH</th>
      <th>2024-12-12 17:00:00</th>
      <td>6058.75</td>
      <td>6087.75</td>
      <td>6055.50</td>
      <td>6082.25</td>
      <td>1152801.0</td>
    </tr>
    <tr>
      <th>2024-12-13 17:00:00</th>
      <td>6051.25</td>
      <td>6084.75</td>
      <td>6041.25</td>
      <td>6077.25</td>
      <td>1027682.0</td>
    </tr>
    <tr>
      <th>2024-12-16 17:00:00</th>
      <td>6076.50</td>
      <td>6090.50</td>
      <td>6064.75</td>
      <td>6072.50</td>
      <td>378263.0</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>6052.00</td>
      <td>6063.50</td>
      <td>6040.75</td>
      <td>6052.75</td>
      <td>278531.0</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>5872.25</td>
      <td>6074.50</td>
      <td>5840.00</td>
      <td>6050.25</td>
      <td>335048.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry               symbol           time               
2024-12-20 13:30:00  ES YOGVNNAOI1OH  2024-12-13 17:00:00   -0.001238
                                      2024-12-16 17:00:00    0.004173
                                      2024-12-17 17:00:00   -0.004032
                                      2024-12-18 17:00:00   -0.029701
Name: close, dtype: float64";

include(DOCS_RESOURCES."/history/tradebars.php");
?>
