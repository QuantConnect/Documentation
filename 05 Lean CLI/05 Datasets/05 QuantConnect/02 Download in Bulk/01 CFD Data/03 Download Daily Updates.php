<p>After you bulk download the CFD dataset, new daily updates are available at 3 PM Coordinated Universal Time (UTC) after each trading day. To unlock local access to the data updates, open the <a href="https://www.quantconnect.com/pricing">Pricing</a> page of your organization and subscribe to at least one of the following data packages:
</p>
<ul>
    <li><span class="button-name">CFD - Daily Updates</span></li>
    <li><span class="button-name">CFD - Hour Updates</span></li>
    <li><span class="button-name">CFD - Minute Updates</span></li>
    <li><span class="button-name">CFD - Second Updates</span></li>
</ul>
<p>You need <a href="https://www.quantconnect.com/docs/v2/cloud-platform/organizations/members#09-Permissions">billing permissions</a> to change the organization's subscriptions.</p>

<p>After you subscribe to dataset updates, to update your local copy of the CFD dataset, use the <a href="https://www.quantconnect.com/datasets/oanda-cfd-data/cli">CLI Command Generator</a> to generate your download command and then run it in a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a>. Alternatively, instead of directly calling the <code>lean data download</code> command, you can place a Python script in the <span class="public-directory-name">data</span> directory of your organization workspace and run it to update your data files. The following example script updates all data resolutions:</p>

<?
$dataset = "CFD Data";
$securityType = "cfd";
$market = "oanda";
$ticker = "xauusd";
$highResolutions = "[\"minute\", \"second\"]";
$extraArgs = "";
include(DOCS_RESOURCES."/datasets/download_bulk_data_script.php");
?>
<p>The preceding script checks the date of the most recent XAUUSD data you have for second and minute resolutions. If there is new data available for either of these resolutions, it downloads the new data files and overwrites your hourly and daily files. If you don't intend to download all resolutions, adjust this script to your needs.</p>
