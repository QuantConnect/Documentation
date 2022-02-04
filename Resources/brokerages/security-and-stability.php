<?php
$getBrokerageStabilityText = function($brokerageName, $deploymentTutorialLink, $statusPageURL=null) {

    $result = "";

    if (!is_null($statusPageURL)) {
        $result .= "<p>Note the following security and stability aspects of our $brokerageName integration.</p>";
        $result .= "<h4>Account Credentials</h4>";
    }
    $result .= "<p>When you <a href='$deploymentTutorialLink'>deploy live algorithms with $brokerageName</a>, we don't save your brokerage account credentials.</p>";

    if (!is_null($statusPageURL)) {
        $result .= "<h4>API Outages</h4>";
        $result .= "<p>We call the $brokerageName API to place live trades. Sometimes the API may be down. Check the <a rel='nofollow' target='_blank' href='$statusPageURL'>$brokerageName status page</a> to see if the API is currently working.</p>";
    }
    echo $result;
}
?>
