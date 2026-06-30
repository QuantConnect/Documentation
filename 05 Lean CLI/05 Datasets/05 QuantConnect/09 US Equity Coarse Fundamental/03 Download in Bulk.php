<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/us-equity-coarse-fundamental#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download the US Equity Coarse Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Coarse Universe" --data-type "Bulk"</pre> 
</div>

<p>To download the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>

<p>After you bulk download the US Equity Coarse Universe dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. Instead of directly calling the <code>lean data download</code> command, you can place the following Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files:</p>

<?
$dataset = "US Equity Coarse Universe";
$dirName = "equity/usa/fundamental/coarse";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script_universe.php");
?>

<p>The preceding script checks the date of the most recent US Equity Coarse Universe data you have. If there is new data available, it downloads the new data files.</p>

<p>To update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>
