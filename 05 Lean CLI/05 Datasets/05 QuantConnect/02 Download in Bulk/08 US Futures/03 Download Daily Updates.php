<p>After you bulk download the US Futures dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. To unlock local access to the data updates, open the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization and subscribe to at least one of the following data packages:
</p><ul>
    <li><span class="button-name">US Futures Daily Updates by AlgoSeek</span></li>
    <li<span class="button-name">>US Futures Hour Updates by AlgoSeek</span></li>
    <li><span class="button-name">US Futures Minute Updates by AlgoSeek</span></li>
    <li><span class="button-name">US Futures Second Updates by AlgoSeek</span></li>
    <li><span class="button-name">US Futures Tick Updates by AlgoSeek</span></li>
</ul>
<p>You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions">billing permissions</a> to change the organization's subscriptions.</p>

<p>After you subscribe to dataset updates, to update your local copy of the US Futures dataset, use the <a href="https://www.quantconnect.com/datasets/algoseek-us-futures/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>. Alternatively, instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions and markets:</p>

<?
$dataset = "US Futures";
$securityType = "future";
$market = "cbot";
$ticker = "zc";
$highResolutions = "[\"minute\", \"second\", \"tick\"]";
$extraArgs = "--market \"CBOT\"";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>The preceding script checks the date of the most recent ZC data you have from the CBOT market for tick, second, and minute resolutions. If there is new data available for any of these resolutions, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions and markets, adjust this script to your needs.</p>

<p>To update your local copy of the US Futures Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Futures Security Master"</pre> 
</div>
