<p>There are limits on the number of nodes that your organization can have, the amount of time that you can train machine learning models in live trading, and the amount of logs that you can produce.</p>

<h4>Node Limits</h4>
<p>The following table shows the number of nodes that each organization tier can have:</p>
<?php echo file_get_contents(DOCS_RESOURCES."/node-limits.html"); ?>

<h4>Training Limits</h4>
<p>Normally, your algorithms must return from the <code>OnData</code> method within 10 minutes, but the <code>Train</code> method lets you increase this amount of time. Training resources are allocated with a <a href='https://en.wikipedia.org/wiki/Leaky_bucket'>leaky bucket algorithm</a> where you can use a maximum of n-minutes in a single training session and the number of minutes available refills over time. This gives you a reservoir of training time when you need it and recharges the reservoir to prepare for the next training session. The reservoir only starts draining after you exceed the standard 10 minutes of training time. The following table shows the amount of extra time that each backtesting and live trading node can spend training machine learning models:</p>
<?php echo file_get_contents(DOCS_RESOURCES."/training-limits.html"); ?>
<p>The refill rate in the table above is based on the real-world clock time, not the backtest clock time. In backtests, the <code>Train</code> method is synchronous, so it will block your algorithm from executing while the model is trained. In live trading, the method runs asynchronously, so ensure your model is ready to use before you continue executing the algorithm. Training occurs on a separate thread, so use a boolean flag to notify your algorithm of the model state.</p>

<h4>Log Limits</h4>
<p>The following table shows the amount of logs that each organization tier can produce:</p>
<?php echo file_get_contents(DOCS_RESOURCES."/log-limits.html"); ?>