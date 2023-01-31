<?
$fieldsToMethod = array(
    "UpdateLimitPrice" => array("C#" => "var limitResponse = ticket.UpdateLimitPrice(limitPrice, tag);", "Python" => "response = ticket.UpdateLimitPrice(limitPrice, tag)"), 
    "UpdateQuantity"  => array("C#" => "var quantityResponse = ticket.UpdateQuantity(quantity, tag);", "Python" => "response = ticket.UpdateQuantity(quantity, tag)"), 
    "UpdateStopPrice"  => array("C#" => "var stopResponse = ticket.UpdateStopPrice(stopPrice, tag);", "Python" => "response = ticket.UpdateStopPrice(stopPrice, tag)"), 
    "UpdateTriggerPrice"  => array("C#" => "var triggerResponse = ticket.UpdateTriggerPrice(triggerPrice, tag);", "Python" => "response = ticket.UpdateTriggerPrice(triggerPrice, tag)"), 
    "UpdateTag" => array("C#" => "var tagResponse = ticket.UpdateTag(tag);", "Python" => "response = ticket.UpdateTag(tag)")
);
?>
To update individual fields of an order, call any of the following methods:</p>

<? foreach ($fieldsToMethod as $key => $value) { if (in_array($key, $supportedMethods)) { ?><li><code><?=$key?></code></li><? } } ?>

    </ul>
        <div class="section-example-container">
            <pre class="csharp">
<? foreach ($fieldsToMethod as $key => $value) { if (in_array($key, $supportedMethods)) { $value["C#"]; echo "

"; } } ?></pre>
            <pre class="python">
<? foreach ($fieldsToMethod as $key => $value) { if (in_array($key, $supportedMethods)) { $value["Python"]; echo "

"; } } ?></pre>
        </div>