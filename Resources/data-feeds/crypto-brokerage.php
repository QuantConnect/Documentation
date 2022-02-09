<?php
$getCryptoBrokerageText = function($brokerageName, $singular=true) {
    echo "<p>The $brokerageName data feed";
    if ($singular) {
        echo " is ";
    } 
    else {
        echo "s are ";
    }
    echo "sourced directly from the $brokerageName API";
    
    if (!$singular) {
        echo "s";
    }
    echo ". WebSockets gather the data and deliver it to the live algorithms running on the platform.</p>";
}
?>
