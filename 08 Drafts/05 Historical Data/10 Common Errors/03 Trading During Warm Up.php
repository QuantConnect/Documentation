<p>
  You can't place trades during the warm-up period because the data feed is replaying historical data for setting algorithm state.
  If you try to place a trade during warm-up, LEAN throws the following error:
</p>

<div class='error-messages'>Backtest Handled Error: This operation is not allowed in Initialize or during warm up: OrderRequest.Submit. Please move this code to the OnWarmupFinished() method.</div></p>
