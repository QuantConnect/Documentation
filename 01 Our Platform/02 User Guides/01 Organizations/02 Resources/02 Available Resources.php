<p>Organizations can subscribe to backtesting, research, and live trading nodes.</p>

<h4>Backtesting Nodes</h4>
<p>You need backtesting nodes to run backtests. The more backtesting nodes your organization has, the more concurrent backtests the you can run. Several models of backtesting nodes are available. Backtesting nodes that are more powerful can run faster backtests and backtest nodes with more RAM can handle more memory-intensive operations like training machine learning models, processing Options data, and managing large universes. The following table shows the specifications of the backtesting node models:<br></p>

<?php echo file_get_contents(DOCS_RESOURCES."/backtest-nodes-table.html"); ?>

<p>Refer to the <a href="https://www.quantconnect.com/pricing"><b>Pricing</b></a> page to see the price of each backtesting node model. You get one free B-MICRO backtesting node in your first organization. This node incurs a 20-second delay when you launch backtests, but the delay is removed and the node is replaced when you subscribe to a new backtesting node in the organization.<br></p>

<h4>Research Nodes</h4>
<p>You need research nodes to use the Research Environment. Several models of research nodes are available. More powerful research nodes allow you to handle more data and run faster computations in your notebooks. The following table shows the specifications of the research node models: </p>

<?php echo file_get_contents(DOCS_RESOURCES."/research-nodes-table.html"); ?>

<p>Refer to the <a href="https://www.quantconnect.com/pricing"><b>Pricing</b></a> page to see the price of each research node model. You get one free R1-4 research node in your first organization, but the node is replaced when you subscribe to a new research node in the organization.</p>

<h4>Live Trading Nodes</h4>
<p>You need a live trading node for each strategy that's deployed to our co-located live trading servers. Several models of live trading nodes are available. More powerful live trading nodes allow you to run algorithms with larger universes and gives you more time for machine learning training. The following table shows the specifications of the live trading node models:<br></p>

<?php echo file_get_contents(DOCS_RESOURCES."/live-trading-nodes-table.html"); ?>

<p>Refer to the <a href="https://www.quantconnect.com/pricing"><b>Pricing</b></a> page to see the price of each live trading node model.</p>

<h4>Naming</h4>
<p>We assign a default name to hardware nodes that includes the model name and an arbitrary string of characters. However, you can <a href='../../tutorials/organizations/handling-resources#05-Rename-Nodes'>rename the nodes in your organization</a> at any time.</p>

<h4>Activity Management</h4>
<p>Manage your organization's nodes from the Algorithm Lab. You can <a href='../../tutorials/organizations/handling-resources#06-Stop-Nodes'>stop running nodes</a> and you can select specific nodes to use when you run backtests, launch research notebooks, or deploy algorithms to live trading.</p>
