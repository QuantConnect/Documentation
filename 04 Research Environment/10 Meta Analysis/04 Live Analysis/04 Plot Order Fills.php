<p>Follow these steps to plot the daily order fills of a live algorithm:</p>

<ol>
    <li>Get the live trading orders.</li>	
	<div class="section-example-container">
	    <pre class="python">orders = api.read_live_orders(project_id)</pre>
	</div>

        <p>The following table provides links to documentation that explains how to get the project Id, depending on the platform you use:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th style='width: 50%'>Platform</th>
            <th>Project Id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cloud Platform</td>
            <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
        </tr>
    </tbody>
</table>
	
	<p>By default, the orders with an ID between 0 and 100. To get orders with an ID greater than 100, pass <code>start</code> and <code>end</code> arguments to the <code class="csharp">ReadLiveOrders</code><code class="python">read_live_orders</code> method. Note that <code>end</code> - <code>start</code> must be less than 100.</p>
	<div class="section-example-container">
	    <pre class="python">orders = api.read_live_orders(project_id, 100, 150)</pre>
	</div>
	<p>The <code class="csharp">ReadLiveOrders</code><code class="python">read_live_orders</code> method returns a list of <code>Order</code> objects, which have the following properties:</p>
	<div data-tree='QuantConnect.Orders.Order'></div>

	<?php include(DOCS_RESOURCES."/qc-api/plot-fills.php"); ?>
</ol>





