<p>
  You can't place trades during the warm-up period because the data feed is replaying historical data for setting algorithm state.
  If you try to place a trade during warm-up, LEAN throws the following error:
</p>

<div class='error-messages'>Backtest Handled Error: This operation is not allowed in <span class='csharp'>Initialize</span><span class='csharp'>initialize</span> or during warm up: OrderRequest.Submit. Please move this code to the <span class='csharp'>OnWarmupFinished()</span><span class='csharp'>on_warmup_finished()</span> method.</div></p>
