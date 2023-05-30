<p>Backtesting nodes enable you to run backtests. The more backtesting nodes your organization has, the more concurrent backtests that you can run. Several models of backtesting nodes are available. Backtesting nodes that are more powerful can run faster backtests and backtest nodes with more RAM can handle more memory-intensive operations like training machine learning models, processing Options data, and managing large universes. The following table shows the specifications of the backtesting node models:<br></p>

<?php echo file_get_contents(DOCS_RESOURCES."/specs/backtest-nodes.html"); ?>

<p>Refer to the <a href="/pricing">Pricing</a> page to see the price of each backtesting node model. You get one free B-MICRO backtesting node in your first organization. This node incurs a 20-second delay when you launch backtests, but the delay is removed and the node is replaced when you subscribe to a new backtesting node in the organization.</p>

<p>GPU nodes perform best on repetitive and highly-parallel tasks like training machine learning models. It takes time to transfer the data to the GPU for computation, so if your algorithm doesn't train machine learning models, the extra time it takes to transfer the data can make it appear that GPU nodes execute backtests slower than CPU nodes.</p>

<p>You can't use backtesting nodes for <a href='/docs/v2/cloud-platform/optimization'>optimizations</a>. The CPU nodes are available on a fair usage basis. The GPU nodes can be shared with a maximum of three members. Depending on the server load, you may use the all of the GPU's processing power.</p>
