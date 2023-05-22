<p>Follow these steps to plot the daily order fills of a live algorithm:</p>

<ol>
    <li>Get the live trading orders.</li>	
	<div class="section-example-container">
	    <pre class="python">orders = api.ReadLiveOrders(project_id)</pre>
	</div>
	<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
	<p>By default, the orders with an ID between 0 and 100. To get orders with an ID greater than 100, pass <code>start</code> and <code>end</code> arguments to the <code>ReadLiveOrders</code> method. Note that <code>end</code> - <code>start</code> must be less than 100.</p>
	<div class="section-example-container">
	    <pre class="python">orders = api.ReadLiveOrders(project_id, 100, 150)</pre>
	</div>
	<p>The <code>ReadLiveOrders</code> method returns a list of <code>Order</code> objects, which have the following properties:</p>
	<div data-tree='QuantConnect.Orders.Order'></div>

	<?php include(DOCS_RESOURCES."/qc-api/plot-fills.php"); ?>
</ol>





