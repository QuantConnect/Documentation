<?php
$getUpdateFrequencyText = function($barBuildingPath, $brokerageName=null) {
    echo "<p>As we <a href='$barBuildingPath'>";
    if ($brokerageName == null) {
        echo "build slices of ticks and bars</a>, we publish them";
    }
    else {
        echo "receive data from $brokerageName</a>, we publish it";
    }    
    
    echo " to the cloud storage system. Lean checks the storage system for new data points at various frequencies, depending on the resolution of your data subscription. When new data points are available, they are injected into your algorithm. The following table shows how often Lean checks the storage system:</p>";
    
    include(DOCS_RESOURCES."/live-dataset-polling-frequency-table.html");

    echo "<p>For example, if we receive a new data point at 9:51am for a dataset for which your algorithm has a daily subscription, your algorithm will discover the new data point between 9:51am and 10:21am.</p>";
}
?>

