<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/forex#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following command to bulk download the data, replacing <code>&lt;resolution&gt;</code> with daily, hour, minute, or second and adjusting the date range:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "FOREX Data" --data-type "Bulk" --resolution "&lt;resolution&gt;" --start "20230101" --end "20230105"</pre> 
</div>

<p>You can also use the <a href="https://www.quantconnect.com/datasets/oanda-forex/cli">CLI Command Generator</a>. For more information, see <a href="/docs/v2/lean-cli/datasets/quantconnect/key-concepts#02-Using-the-CLI">Using the CLI</a>.</p>

<p>After you bulk download the Forex dataset, new daily updates are available at 3 PM Coordinated Universal Time (UTC) after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions:</p>

<?
$dataset = "FOREX Data";
$securityType = "forex";
$market = "oanda";
$ticker = "eurusd";
$highResolutions = "[\"minute\", \"second\"]";
$extraArgs = "";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>To update your local dataset, the preceding script checks the date of the most recent EURUSD data you have for all resolutions. If there is new data available for either of these resolutions, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions, adjust this script to your needs.</p>
