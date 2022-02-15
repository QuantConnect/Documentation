<?php
    echo "<p>QuantConnect enables you to run your algorithms in live mode with real-time market data. Deploy your algorithms using QuantConnect because our infrastructure is battle-tested. We have successfully hosted more than ";
    echo file_get_contents(DOCS_RESOURCES."/kpis/live-algorithms-count.php");
    echo " live algorithms and have had more than ";
    echo file_get_contents(DOCS_RESOURCES."/kpis/volume-traded.php");
    echo " in volume traded on our servers since 2015. The algorithms that our members create are run on co-located servers and the trading infrastructure is maintained at all times by our team of engineers. It's common for members to achieve 6-months of uptime with no interruptions.</p>";
?>
