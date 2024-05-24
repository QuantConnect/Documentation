<p>
	Live trading nodes enable you to deploy live algorithms to our professionally-managed, co-located servers. 
	You need a live trading node for each algorithm that you deploy to our co-located servers. 
	Several models of live trading nodes are available. 
	More powerful live trading nodes allow you to run algorithms with larger universes and give you <a href='/docs/v2/cloud-platform/organizations/resources#08-Training-Quotas'>more time for machine learning training</a>.
	Each security subscription requires about 5MB of RAM. The following table shows the specifications of the live trading node models:
</p>

<? include(DOCS_RESOURCES."/specs/live-trading-nodes.html"); ?>

<p>Refer to the <a href="/pricing">Pricing</a> page to see the price of each live trading node model.</p>

<p>
	To view the status of all of your organization's nodes, see the <a href='/docs/v2/cloud-platform/projects/ide#07-Manage-Nodes'>Resources panel</a> of the IDE. 
	When you deploy an algorithm, it uses the best-performing resource by default, but you can <a href='/docs/v2/cloud-platform/projects/ide#07-Manage-Nodes'>select a specific resource to use</a>.
</p>

<p>	
	The CPU nodes are available on a fair usage basis while the GPU nodes can be shared with a maximum of two members. 
	Depending on the server load, you may use all of the GPU's processing power.
	GPU nodes perform best on repetitive and highly-parallel tasks like training machine learning models. 
	It takes time to transfer the data to the GPU for computation, so if your algorithm doesn't train machine learning models, the extra time it takes to transfer the data can make it appear that GPU nodes run slower than CPU nodes.
</p>
