<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/index-options#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download the US Index Option Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Index Option Universe" --data-type "Bulk" --start "20250403" --end "20250403"</pre> 
</div>

<p>To download the US Index Options data for a resolution, run the following command, replacing <code>&lt;resolution&gt;</code> with daily, hour, or minute and adjusting the date range:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Index Options" --data-type "Bulk" --resolution "&lt;resolution&gt;" --start "20230101" --end "20230105"</pre> 
</div>

<p>You can also use the <a href="https://www.quantconnect.com/datasets/algoseek-us-index-options/cli">CLI Command Generator</a>. For more information, see <a href="/docs/v2/lean-cli/datasets/quantconnect/key-concepts#03-Using-the-CLI">Using the CLI</a>.</p>

<p>After you bulk download the US Index Options dataset, new daily updates are available at 8 PM Coordinated Universal Time (UTC) two days after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions:</p>

<?
$dataset = "US Index Options";
$securityType = "indexoption";
$market = "usa";
$ticker = "spx";
$highResolutions = "[\"minute\"]";
$extraArgs = "";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>The preceding script checks the date of the most recent minute resolution data you have for SPX. If there is new minute data available, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions, adjust this script to your needs.</p>

<p>To update your local copy of the US Index Option Universe data, run the following command, or use the universe auto-update script below:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Index Option Universe" --data-type "Bulk" --start "20250403" --end "20250403"</pre> 
</div>

<?
$dataset = "US Index Option Universe";
$dirName = "indexoption/usa/universes";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script_universe.php");
?>
