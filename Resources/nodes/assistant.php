<p>
	Assistant nodes are the servers that run your assistants—a harness for quant finance that wraps your AI models in the tools, data, and context they need to be productive.
	Assistant nodes enable you to deploy assistant tasks.
	The more assistant nodes your organization has, the more concurrent assistant tasks that you can run.
	More powerful assistant nodes have more cores and RAM to handle larger, more demanding tasks.
	The following table shows the specifications of the assistant node models:
</p>

<? include(DOCS_RESOURCES."/specs/assistant-nodes.html"); ?>

<p>
	Refer to the <a href="/pricing">Pricing</a> page to see the price of each assistant node model.
	Your first organization includes one free A-MICRO assistant node, which is capped at 100,000 tokens per month.
	The token cap is lifted and the node is replaced when you <a href='/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers'>upgrade your organization to a paid tier</a> and <a href='/docs/v2/cloud-platform/organizations/resources#15-Add-Nodes'>add a new assistant node</a>.
	Paid assistant nodes don't have the 100,000-token cap, but a fair use token allowance applies over a weekly rolling window.
	If you use tokens beyond the weekly quota, upgrade to the next assistant node model or <a href='/docs/v2/ai-assistance/bring-your-own-key'>bring your own key</a>.
</p>

<p>
	To view the status of all of your organization's nodes, see the <a href='/docs/v2/cloud-platform/projects/ide#08-Manage-Nodes'>Resources panel</a> of the IDE.
	When you launch an assistant task, it uses the best-performing resource by default, but you can <a href='/docs/v2/cloud-platform/projects/ide#08-Manage-Nodes'>select a specific resource to use</a>.
</p>
