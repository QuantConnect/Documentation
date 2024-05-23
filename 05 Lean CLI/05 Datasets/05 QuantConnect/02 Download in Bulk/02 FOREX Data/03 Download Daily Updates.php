<p>After you bulk download the Forex dataset, new daily updates are available at 3 PM Coordinated Universal Time (UTC)  after each trading day. To gain access to the data updates, open the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization and subscribe to at least one of the following data packages:</p>
<ul>
    <li>OANDA Forex - Daily Updates</li>
    <li>OANDA Forex - Hour Updates</li>
    <li>OANDA Forex - Minute Updates</li>
    <li>OANDA Forex - Second Updates</li>
</ul>

<p>You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#08-Permissions">billing permissions</a> to change the organization's subscriptions.</p>


<p>After you subscribe to dataset updates, to update your local copy of the Forex dataset, use the <a href="https://www.quantconnect.com/datasets/oanda-forex/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>. Alternatively, instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions:</p>

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