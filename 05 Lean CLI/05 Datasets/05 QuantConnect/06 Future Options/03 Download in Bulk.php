<p>After you unlock local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/future-options#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the prerequisite datasets.</p>

<p>To download or update your local copy of the US Futures Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Futures Security Master"</pre> 
</div>

<p>To download or update your local copy of the US Future Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Future Universe" --data-type "Bulk" --start "20250403" --end "20250403"</pre> 
</div>

<p>To download or update your local copy of the US Future Option Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Future Option Universe" --data-type "Bulk" --start "20250403" --end "20250403"</pre> 
</div>

<p>To download or update your local copy of the US Future Options data, use the <a href="https://www.quantconnect.com/datasets/algoseek-us-future-options/cli">CLI Command Generator</a> on the dataset listing to generate your download command, then run it in your organization workspace. For more information, see <a href="/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI">Using the CLI</a>. You also need the underlying US Futures data; see <a href="/docs/v2/lean-cli/datasets/quantconnect/futures#03-Download-in-Bulk">US Futures</a>.</p>

<p>After you bulk download the US Future Options dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions and markets:</p>

<?
$dataset = "US Future Options";
$securityType = "futureoption";
$market = "cme";
$ticker = "es";
$highResolutions = "[\"minute\"]";
$extraArgs = "";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>The preceding script checks the date of the most recent ES data you have from the CME market for minute resolution. If there is new data available, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions and markets, adjust this script to your needs.</p>
