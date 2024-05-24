<p>
	Backtesting nodes enable you to run backtests. The more backtesting nodes your organization has, the more concurrent backtests that you can run. Several models of backtesting nodes are available. 
	Backtesting nodes that are more powerful can run faster backtests and backtest nodes with more RAM can handle more memory-intensive operations like training machine learning models, processing Options data, and managing large universes. 
	The following table shows the specifications of the backtesting node models:
</p>

<? include(DOCS_RESOURCES."/specs/backtest-nodes.html"); ?>

<p>
	Refer to the <a href="/pricing">Pricing</a> page to see the price of each backtesting node model. 
	You get one free B-MICRO backtesting node in your first organization. 
	This node incurs a 20-second delay when you launch backtests, but the delay is removed and the node is replaced when <a href='/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers'>upgrade your organization to a paid tier</a> and <a href='/docs/v2/cloud-platform/organizations/resources#14-Add-Nodes'>add a new backtesting node</a>.
</p>

<p>
	To view the status of all of your organization's nodes, see the <a href='/docs/v2/cloud-platform/projects/ide#07-Manage-Nodes'>Resources panel</a> of the IDE. 
	When you run a backtest, it uses the best-performing resource by default, but you can <a href='/docs/v2/cloud-platform/projects/ide#07-Manage-Nodes'>select a specific resource to use</a>.
</p>

<p>	
	The CPU nodes are available on a fair usage basis while the GPU nodes can be shared with a maximum of three members. 
	Depending on the server load, you may use all of the GPU's processing power.
	GPU nodes perform best on repetitive and highly-parallel tasks like training machine learning models. 
	It takes time to transfer the data to the GPU for computation, so if your algorithm doesn't train machine learning models, the extra time it takes to transfer the data can make it appear that GPU nodes run slower than CPU nodes.
</p>
