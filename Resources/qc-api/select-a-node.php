<p>You need to select a node to deploy a live algorithm. To view the live trading nodes in your organization, call the <code>ReadNodes</code> method with your organization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var nodes = api.ReadNodes(organizationId);</pre>
    <pre class="python">nodes = api.ReadNodes(organization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>ReadNodes</code> method returns a <code>NodeList</code> object, which have the following attributes:</p>

<div data-tree='QuantConnect.Api.NodeList'></div>

<p>To select the first available live trading node in your organization, run:</p>

<div class="section-example-container">
    <pre class="csharp">var nodeId = nodes.LiveNodes.Where(x => x.Busy == false).FirstOrDefault().Id;</pre>
    <pre class="python">node_id = [x for x in nodes.LiveNodes if not x.Busy][0].Id</pre>
</div>
