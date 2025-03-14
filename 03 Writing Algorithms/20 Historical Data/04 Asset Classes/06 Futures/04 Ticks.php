<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FutureChainProvider.GetFutureContractList(future.Symbol, Time)
            .OrderBy(symbol => symbol.ID.Date).First();";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        contract_symbols = self.future_chain_provider.get_future_contract_list(future.symbol, self.time)
        symbol = sorted(contract_symbols, key=lambda symbol: symbol.id.date)[0]";
$assetClass = "Future";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#04-Ticks";
$dataType = "Tick";
$supportsTradeData = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th>askprice</th>
      <th>asksize</th>
      <th>bidprice</th>
      <th>bidsize</th>
      <th>lastprice</th>
      <th>openinterest</th>
      <th>quantity</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20 13:30:00</th>
      <th rowspan='5' valign='top'>ES YOGVNNAOI1OH</th>
      <th>2024-12-17 09:30:00.001</th>
      <td>NaN</td>
      <td>NaN</td>
      <td>NaN</td>
      <td>NaN</td>
      <td>6052.75</td>
      <td>NaN</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.001</th>
      <td>0.0</td>
      <td>0.0</td>
      <td>6052.75</td>
      <td>33.0</td>
      <td>6052.75</td>
      <td>NaN</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.001</th>
      <td>6053.0</td>
      <td>11.0</td>
      <td>0.00</td>
      <td>0.0</td>
      <td>6053.00</td>
      <td>NaN</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.001</th>
      <td>6053.0</td>
      <td>10.0</td>
      <td>0.00</td>
      <td>0.0</td>
      <td>6053.00</td>
      <td>NaN</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.001</th>
      <td>6053.0</td>
      <td>9.0</td>
      <td>0.00</td>
      <td>0.0</td>
      <td>6053.00</td>
      <td>NaN</td>
      <td>0.0</td>
    </tr>
  </tbody>
</table>
</div>
";

$filteredDataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th>lastprice</th>
      <th>quantity</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20 13:30:00</th>
      <th rowspan='5' valign='top'>ES YOGVNNAOI1OH</th>
      <th>2024-12-17 09:30:00.001</th>
      <td>6052.75</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.002</th>
      <td>6053.00</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.002</th>
      <td>6053.00</td>
      <td>2.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.003</th>
      <td>6052.75</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>2024-12-17 09:30:00.003</th>
      <td>6052.75</td>
      <td>3.0</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/ticks.php");
?>
