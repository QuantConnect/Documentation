<p>
	Live trading nodes enable you to deploy live algorithms to our professionally-managed, co-located servers. 
	The data center depends on the <a href='/docs/v2/cloud-platform/live-trading/getting-started#06-Redundancy'>region</a> you deploy to. 
	You need a live trading node for each algorithm that you deploy to our co-located servers. 
	Several models of live trading nodes are available. 
	More powerful live trading nodes allow you to run algorithms with larger universes and give you <a href='/docs/v2/cloud-platform/organizations/resources#08-Training-Quotas'>more time for machine learning training</a>.
	The following table shows the specifications of the live trading node models:
</p>

<? include(DOCS_RESOURCES."/specs/live-trading-nodes.html"); ?>

<p>
	Each security subscription requires about 5MB of RAM, so the 512MB L-MICRO node holds on the order of 100 subscriptions before the data feed alone exhausts it. 
	Size the node so your algorithm averages below 80% of its RAM limit, which is 410MB on the L-MICRO node. 
	LEAN checks a smoothed 10-minute average against the limit and separately allows a single raw sample to reach twice the limit, so a brief spike during universe selection or a bulk history request doesn't stop the algorithm, but a sustained level above the limit does. 
	For the checks themselves, see <a href='/docs/v2/writing-algorithms/key-concepts/debugging-tools#07-Memory-Metrics'>Memory Metrics</a>, and for how to reduce the footprint before buying a larger node, see <a href='/docs/v2/writing-algorithms/key-concepts/algorithm-performance'>Algorithm Performance</a>.
</p>

<p>Refer to the <a href="/pricing">Pricing</a> page to see the price of each live trading node model.</p>

<p>
	To view the status of all of your organization's nodes, see the <a href='/docs/v2/cloud-platform/projects/ide#08-Manage-Nodes'>Resources panel</a> of the IDE. 
	When you deploy an algorithm, it uses the best-performing resource by default, but you can <a href='/docs/v2/cloud-platform/projects/ide#08-Manage-Nodes'>select a specific resource to use</a>.
</p>

<p>	
	The CPU nodes are available on a fair usage basis while the GPU nodes can be shared with a maximum of two members. 
	Depending on the server load, you may use all of the GPU's processing power.
	GPU nodes perform best on repetitive and highly-parallel tasks like training machine learning models. 
	It takes time to transfer the data to the GPU for computation, so if your algorithm doesn't train machine learning models, the extra time it takes to transfer the data can make it appear that GPU nodes run slower than CPU nodes.
</p>
