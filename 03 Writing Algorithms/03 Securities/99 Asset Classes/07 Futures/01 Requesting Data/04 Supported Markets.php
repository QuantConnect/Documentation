<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/market-future.html"); ?>

<p>Historical data for backtesting is unavailable for <code>ICE</code>, <code>INDIA</code>, <code>SGX</code> and <code>NYSELIFFE</code>. In live trading, LEAN sources Futures data from your <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage</a> or a <a href='/docs/v2/writing-algorithms/live-trading/data-providers'>third-party data provider</a>.</p>

<p>You don't need to pass a <code>market</code> argument to the <code class="csharp">AddFutureContract</code><code class="python">add_future_contract</code> method because the contract <code>Symbol</code> already contains the market.</p>
