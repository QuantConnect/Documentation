<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/us-equity#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>

<p>To download the US Equities data for a resolution, run the following command, replacing <code>&lt;resolution&gt;</code> with daily, hour, minute, second, or tick and adjusting the date range:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equities" --data-type "Bulk" --resolution "&lt;resolution&gt;" --start "20230101" --end "20230105"</pre> 
</div>

<p>You can also use the <a href="https://www.quantconnect.com/datasets/algoseek-us-equities/cli">CLI Command Generator</a> on the dataset listing to generate the command. For more information about the interactive wizard and the CLI Command Generator, see <a href="/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI">Using the CLI</a>.</p>

<p>After you bulk download the US Equities dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. Subscribe to at least one of the <span class="button-name">US Equity ... Updates by AlgoSeek</span> data packages to unlock the updates. Instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions:</p>

<?
$dataset = "US Equities";
$securityType = "equity";
$market = "usa";
$ticker = "spy";
$highResolutions = "[\"minute\", \"second\", \"tick\"]";
$extraArgs = "";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>The preceding script checks the date of the most recent SPY data you have for all resolutions. If there is new data available for any of these resolutions, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions, adjust this script to your needs.</p>

<p>To update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>
