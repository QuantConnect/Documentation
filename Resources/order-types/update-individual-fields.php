<?
    $fieldsToMethod = array(
      "UpdateLimitPrice" => array("C#" => "var limitResponse = ticket.UpdateLimitPrice(limitPrice, tag);", "Python" => "response = ticket.update_limit_price(limit_price, tag)"), 
      "UpdateQuantity"  => array("C#" => "var quantityResponse = ticket.UpdateQuantity(quantity, tag);", "Python" => "response = ticket.update_quantity(quantity, tag)"), 
      "UpdateStopPrice"  => array("C#" => "var stopResponse = ticket.UpdateStopPrice(stopPrice, tag);", "Python" => "response = ticket.update_stop_price(stop_price, tag)"), 
      "UpdateTriggerPrice"  => array("C#" => "var triggerResponse = ticket.UpdateTriggerPrice(triggerPrice, tag);", "Python" => "response = ticket.update_trigger_price(trigger_price, tag)"), 
      "UpdateTag" => array("C#" => "var tagResponse = ticket.UpdateTag(tag);", "Python" => "response = ticket.update_tag(tag)")
    );
  
    echo "
        <p>To update individual fields of an order, call any of the following methods:</p>
        <ul>
    ";
    
    foreach ($fieldsToMethod as $key => $value)
    {
        if (in_array($key, $supportedMethods))
        {
            echo "<li><code>{$key}</code></li>";
        }
    }
  
    echo "</ul>";
    
            
    echo "<div class=\"section-example-container\">
              <pre class=\"csharp\">";
        
    foreach ($fieldsToMethod as $key => $value)
    {
        if (in_array($key, $supportedMethods))
        {
            echo $value["C#"];
            echo "

";
        }
    }
    echo "</pre>
<pre class=\"python\">";
    foreach ($fieldsToMethod as $key => $value)
    {
        if (in_array($key, $supportedMethods))
        {
            echo $value["Python"];
            echo "

";
        }
    }
    echo "</pre></div>";
?>