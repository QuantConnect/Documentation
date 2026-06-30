<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/us-etf-constituents#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download the US ETF Constituents data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US ETF Constituents" --data-type "Bulk" --start "20090601" --end "20500101"</pre> 
</div>

<p>To download the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>

<p>After you bulk download the US ETF Constituents dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place the following Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files:</p>

<?
$dataset = "US ETF Constituents";
$dirName = "equity/usa/universes/etf/spy";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script_universe.php");
?>

<p>The preceding script checks the date of the most recent SPY data you have. If there is new data available for SPY, it downloads the new data files for all of the ETFs. You may need to adjust this script to fit your needs.</p>

<p>To update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>
