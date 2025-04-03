<p>After you bulk download the US Equity Options dataset, new daily updates are available at 8 PM Coordinated Universal Time (UTC) two days after each trading day. For example, the minute resolution data for Monday is available on Wednesday at 8 PM UTC. To unlock local access to the data updates, open the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization and subscribe to at least one of the following data packages:</p>
<ul>
    <li><span class="button-name">US Equity Options Daily Updates by AlgoSeek</span></li>
    <li><span class="button-name">US Equity Options Minute Updates by AlgoSeek</span></li>
    <li><span class="button-name">US Equity Options Hour Updates by AlgoSeek</span></li>
</ul>
<p>You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions">billing permissions</a> to change the organization's subscriptions.</p>

<p>
    After you subscribe to dataset updates, to update your local copy of the US Equity Options dataset, use the <a href="https://www.quantconnect.com/datasets/algoseek-us-equity-options/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>.
    Alternatively, instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. 
    The following example script updates all data resolutions:
</p>

<?
$dataset = "US Equity Options";
$securityType = "option";
$market = "usa";
$ticker = "aapl";
$highResolutions = "[\"minute\"]";
$extraArgs = "--option-style \"American\"";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>

<p>The preceding script checks the date of the most recent minute resolution data you have for AAPL. If there is new minute data available, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions, adjust this script to your needs.</p>

<p>To update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>


<p>To update your local copy of the US Equity Option Universe data, see <a href='/docs/v2/lean-cli/datasets/quantconnect/download-in-bulk/us-equity-option-universe#03-Download-Daily-Updates'>Download Daily Updates</a>.</p>
