<?php
include(DOCS_RESOURCES."/cli/install/cli/stay-up-to-date.php");
$isCLIDocs = true;
$getUpdateText($isCLIDocs);
?>

<h4>Keep the Docker Images Up-To-Date</h4>
<p>
    Various commands like <code>lean backtest</code>, <code>lean optimize</code> and <code>lean research</code> run the LEAN engine in a Docker container to ensure all the required dependencies are available.
    When you run these commands for the first time the Docker image containing LEAN and its dependencies is downloaded and stored.
    Run these commands with the <code>--update</code> flag to update the images they use.
    Additionally, these commands automatically perform a version check once a week and update the image they use when you are using an outdated Docker image.
</p>
