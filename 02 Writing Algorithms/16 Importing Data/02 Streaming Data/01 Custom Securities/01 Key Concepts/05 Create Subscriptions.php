<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/create-subscriptions.html"); ?>

<p>The <code>resolution</code> argument should match the resolution of your custom dataset. The lowest reasonable resolution is every minute. Anything more frequent than every minute is very slow to execute. The frequency that LEAN checks the data source depends on the <code>resolution</code> argument. The following table shows the polling frequency of each resolution:</p> 

<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>
