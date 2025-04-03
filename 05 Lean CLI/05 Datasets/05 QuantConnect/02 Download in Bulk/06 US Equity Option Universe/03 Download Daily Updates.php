<p>
	After you bulk download the US Equity Option Universe dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. 
	To unlock local access to the data updates, open the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization and subscribe to the <span class="button-name">US Equity Option Universe Updates by QuantConnect</span> data package.
	You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions">billing permissions</a> to change the organization's subscriptions.
</p>

<p>
	After you subscribe to dataset updates, to update your local copy of the US Equity Option Universe dataset, use the <a href="https://www.quantconnect.com/datasets/algoseek-us-equity-option-universe/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>. 
    Alternatively, instead of directly calling the <code>lean data download</code> command, you can place the following Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. 
</p>

<?
$dataset = "US Equity Option Universe";
$dirName = "option/usa/universes";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script_universe.php");
?>

<p>The preceding script checks the date of the most recent US Equity Option Universe data you have. If there is new data available, it downloads the new data files.</p>

<p>To update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>
