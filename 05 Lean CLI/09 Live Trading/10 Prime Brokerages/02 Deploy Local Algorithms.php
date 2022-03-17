<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter the number of the organization that has a subscription for the Terminal Link module.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the organization with the Terminal Link module subscription:
1) Organization 1
2) Organization 2
3) Organization 3
Enter an option: 1</pre>
</div>
</li>


<li>Enter the environment to run in. This property must be either <code>Production</code> or <code>Beta</code>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Environment (Production, Beta): Production</pre>
</div>
</li>

<li>Enter the host and port of the Bloomberg server.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Server host: 127.0.0.1
Server port: 8194</pre>
</div>
</li>

<li>Enter the path to the symbol map file.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Path to symbol map file: ~/Documents/symbol-map-file.json</pre>
</div>
        The symbol map file must be a JSON file (comments are supported) in which the keys are the Bloomberg symbol names and the values are the corresponding QuantConnect symbols. This content can be used as an example:
<div class='section-example-container'>
<pre>
/* This is a manually created file that contains mappings from Bloomberg's own naming to original symbols defined by respective exchanges. */
{
    // Example:
    /*\"SPY US Equity\": {
        \"Underlying\": \"SPY\",
        \"SecurityType\": \"Equity\",
        \"Market\": \"usa\"
    }*/
}
</pre>
</div>
</li>


<li>Enter your EMSX configuration (properties followed by <code>[]</code> can be skipped by pressing enter).
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
EMSX broker: someValue
EMSX user timezone [UTC]:
EMSX account []:
EMSX strategy []:
EMSX notes []:
EMSX handling []:</pre>
</div>
</li>

<li>Enter whether modification must be allowed.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Allow modification (yes/no): no</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = true;

$getDeployLocalAlgorithmsText("Terminal Link", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>