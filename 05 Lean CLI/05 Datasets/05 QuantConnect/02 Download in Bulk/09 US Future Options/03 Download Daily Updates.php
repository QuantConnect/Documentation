<p>After you bulk download the US Future Options dataset, new daily updates are available at 7 AM Eastern Time (ET) after each trading day. To unlock local access to the data updates, <a href="https://www.quantconnect.com/contact">contact us</a>. You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions">billing permissions</a> to change the organization's subscriptions.</p>

<p>After you subscribe to dataset updates, to update your local copy of the US Future Options dataset, use the <a href="https://www.quantconnect.com/datasets/algoseek-us-future-options/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>. Alternatively, instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions and markets:</p>

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
