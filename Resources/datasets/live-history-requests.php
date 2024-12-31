<p>In live trading, if you make a history request for minute data at noon and the history period covers the start of the previous day to the present moment, the data from the previous day will be backtest data. The data of the current day will be live data that we collected throughout the morning. If you make this history request in a backtest, you might get slightly different data for the current day because of post-processing from the data vendor.</p>

<p>
  In cloud deployments, QuantConnect is the default data provider. 
  If you remove it and use a different data provider, you won't get any price data form QuantConnect. 
  However, we provide the following datasets:
</p>

<? include(DOCS_RESOURCES."/datasets/auxiliary-dataset-list.html"); ?>
