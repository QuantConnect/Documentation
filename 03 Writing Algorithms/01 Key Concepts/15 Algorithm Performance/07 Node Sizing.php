<p>
  Change the node last.
  A node upgrade is the only step in this list that costs money on every backtest, and it is the step with the smallest effect on a well-written algorithm.
</p>

<h4>Extra Cores Do Not Speed Up Your Strategy Logic</h4>

<p>
  LEAN is multi-threaded and loads data in parallel, but your algorithm's events fire synchronously in backtesting.
  As the <a href="/docs/v2/writing-algorithms/key-concepts/algorithm-engine#04-Threads-in-LEAN">Threads in LEAN</a> page states, the primary bottleneck to LEAN execution is executing client code.
</p>

<p>
  The cores beyond the first serve LEAN's data loading, not your strategy.
  A backtest that leaves most of its cores idle is behaving normally, and it tells you the algorithm spends its time in your code rather than waiting on data.
  You cannot split one backtest across several cores or several nodes by writing the algorithm differently.
</p>

<h4>Throughput Comes From More Nodes, Not Bigger Ones</h4>

<p>
  One backtest runs on one node.
  To run more backtests at the same time, add nodes.
  When your backtests use a fraction of the RAM and cores of the node they run on, several smaller nodes give your organization more total throughput than the same spend on fewer large ones.
</p>

<h4>Backtesting Node Specifications</h4>

<p>The following table shows the specifications of the <a href="/docs/v2/cloud-platform/organizations/resources#02-Backtesting-Nodes">backtesting node</a> models:</p>

<? include(DOCS_RESOURCES."/specs/backtest-nodes.html"); ?>

<p>
  The B2-8, B4-12, and B8-16 nodes run at the same clock speed, so single-threaded algorithm code runs at the same speed on all three.
  What the larger nodes add is RAM headroom and cores for data loading.
  The B-MICRO and B4-16-GPU nodes run at a lower clock speed, so they execute algorithm code more slowly.
</p>

<h4>Budget the Memory Before You Pick a Model</h4>

<p>
  As a rule of thumb, each security subscription costs about 5MB of RAM, so the 512MB <a href="/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes">L-MICRO</a> node holds on the order of 100 subscriptions before the data feed alone exhausts it.
  Everything else the algorithm keeps resident, such as indicators, history results, rolling windows, and machine learning models, comes out of the same budget.
</p>

<p>
  Size the node so the algorithm averages below 80% of the model's RAM limit, which is 410MB on a 512MB node.
  LEAN checks a smoothed average against the limit, which spans about a minute in backtests and about 10 minutes in live trading, and separately allows a single raw sample to reach twice the limit, so a brief spike does not stop the algorithm but a sustained level above the limit does.
  The <a href="/docs/v2/writing-algorithms/key-concepts/debugging-tools#07-Memory-Metrics">Memory Metrics</a> page describes both checks and the log message LEAN writes when you cross 80%.
</p>

<p>
  Universe selection, Option chains, and bulk history requests are the usual sources of memory spikes.
  A single <span class="csharp"><code>History</code></span><span class="python"><code>history</code></span> call across a few hundred symbols builds one large table in memory, so it is often the peak an otherwise small algorithm has to be sized for.
  Warming indicators from the universe's own data instead of from per-symbol history requests removes that peak rather than paying for it in a larger node.
</p>

<p>
  Choose a GPU node only when the algorithm offloads computation to the device, such as training a deep learning model with a framework that uses the GPU.
  It takes time to transfer data to the GPU, and the GPU node runs at a lower clock speed than the standard nodes, so an algorithm that does not use the GPU runs more slowly on it.
</p>

<h4>Test a Node Before You Commit to It</h4>

<p>
  You don't have to reason about which node your algorithm needs.
  You can <a href="/docs/v2/cloud-platform/organizations/resources#15-Add-Nodes">add</a> and <a href="/docs/v2/cloud-platform/organizations/resources#16-Remove-Nodes">remove</a> nodes at any time, so add the model you want to evaluate, run your heaviest strategy on it, and remove it if it doesn't earn its cost.
</p>

<p>
  Adding or removing a node renews your entire subscription period.
  When you add one, QuantConnect charges you the difference in price between your current subscription and the new one, which covers both the node and the extension of the subscription.
  When you remove one, the period renews in the same way and your organization receives a pro-rated credit that is applied to your next invoice.
</p>
