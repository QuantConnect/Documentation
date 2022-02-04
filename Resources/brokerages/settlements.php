<?php

$getSettlementText() = function($usBrokerage, $cashAccount=null, $marginAccount=null, $equities=null, $options=null) {

    $result = "<p>";

    if ($usBrokerage) {
        if ($cashAccount) {
            $result .= "If you trade with a cash account, trades settle immediately. ";
        }

        if ($marginAccount) {
            $result .= "If you trade with a margin account, ";

            if ($equities) {
                $result .= "Equity trades settle 3 days after the transaction date (T+3)";
                if ($options) {
                    $result .= " and ";
                }
            }

            if ($options) {
                $result .= "Option trades settle on the business day following the transaction (T+1)."
            }
        }
    }
    else {
        $result .= "Trades settle immediately after the transaction."
    }

    $result .= "</p>";

    $result .=  "
    <div class=\"section-example-container\">
        <pre class=\"csharp\">security.SettlementModel = new ImmediateSettlementModel();</pre>
        <pre class=\"python\">security.SettlementModel = ImmediateSettlementModel()</pre>
    </div>
    "

   echo $result;
}

?>