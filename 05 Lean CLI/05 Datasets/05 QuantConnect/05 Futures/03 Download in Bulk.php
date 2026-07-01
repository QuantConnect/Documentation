<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/futures#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download or update your local copy of the US Futures Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Futures Security Master"</pre> 
</div>

<p>To download or update your local copy of the US Future Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Future Universe" --data-type "Bulk" --start "20250403" --end "20250403"</pre> 
</div>

<p>To download or update your local copy of the US Futures data for a resolution, run the following command, replacing <code>&lt;resolution&gt;</code> with daily, hour, minute, second, or tick and adjusting the date range:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Futures" --data-type "Bulk" --resolution "&lt;resolution&gt;" --start "20230101" --end "20230105"</pre> 
</div>

<p>You can also use the <a href="https://www.quantconnect.com/datasets/algoseek-us-futures/cli">CLI Command Generator</a>. For more information, see <a href="/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI">Using the CLI</a>.</p>

<p>After you bulk download the US Futures dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions and markets:</p>

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
