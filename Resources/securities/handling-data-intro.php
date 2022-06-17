<?php
$getIntroText = function($dataFormat, $securityName) {

    echo "
    <p>LEAN passes the data you request to the <code>OnData</code> method so you can make trading decisions.<span class='csharp'> The default <code>OnData</code> method accepts a <code>Slice</code> object, but you can define additional <code>OnData</code> methods that accept different data types. For example, if you define an <code>OnData</code> method that accepts a <code>{$dataFormat}</code> argument, it only receives <code>{$dataFormat}</code> objects.</span> The <code>Slice</code> object that the <code>OnData</code> method receives groups all the data together at a single moment in time.</p>
    
    <p>All the data formats use <code>DataDictionary</code> objects to group data by <code>Symbol</code> and provide easy access to information. The plural of the type denotes the collection of objects. For instance, the <code>{$dataFormat}s</code> <code>DataDictionary</code> is made up of <code>{$dataFormat}</code> objects. To access individual data points in the dictionary, you can index the dictionary with the {$securityName} ticker or <code>Symbol</code>, but we recommend you use the <code>Symbol</code>.</p>
    ";
}


?>
