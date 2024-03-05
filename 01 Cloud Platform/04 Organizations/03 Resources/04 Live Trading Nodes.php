<p>Live trading nodes enable you to deploy live algorithms to our professionally-managed, co-located servers. Several models of live trading nodes are available. More powerful live trading nodes allow you to run algorithms with larger universes and give you more time for machine learning training. Each security subscription requires about 5MB of RAM. The following table shows the specifications of the live trading node models:<br></p>

<?php echo file_get_contents(DOCS_RESOURCES."/specs/live-trading-nodes.html"); ?>

<p>Refer to the <a href="/pricing">Pricing</a> page to see the price of each live trading node model.</p>

<p>By default, the best-performing resource is selected when you deply a live algorithm, but you can <a href='/docs/v2/cloud-platform/projects/ide#07-Manage-Nodes'>select a specific resource to use</a>.</p>

<p>GPU nodes perform best on repetitive and highly-parallel tasks like training machine learning models. It takes time to transfer the data to the GPU for computation, so if your algorithm doesn't train machine learning models, the extra time it takes to transfer the data can make it appear that GPU nodes run slower than CPU nodes.</p>

<p>The CPU nodes are available on a fair usage basis. The GPU nodes can be shared with a maximum of two members. Depending on the server load, you may use the all of the GPU's processing power.</p>
