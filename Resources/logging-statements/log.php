<?php

$getLogText = function($isOnOurPlatform) {
    $result = "<p>Log statements are added to the log file while your algorithm continues executing. Logging dataset information is not permitted. Use <code>Log</code> statements to debug your backtests and live trading algorithms.</p> <p>";
    
    if ($isOnOurPlatform) {
        $result .= "L";
    }
    else
    {
        $result .= "If you execute algorithms in QC Cloud, l" ;
    }
    $result .= "og length is <a href='/docs/v2/our-platform/organizations/resources#09-Log-Quotas'>capped by organization tier</a>. If your organization hits the daily limit, <a href=\"/contact\">contact us</a>. In cloud projects, the log files can store up to 100,000 lines for up to one year. If you log more than 100,000 lines or some lines become a year old, we remove the oldest lines in the file so your project stays within the quota.</p> <p>If you log the same content multiple times, only the first instance is added to the log file. To bypass this rate-limiting, add a timestamp to your log messages.</p> <p>To record the algorithm state when the algorithm stops executing, add log statements to the <code>OnEndOfAlgorithm</code> event handler.</p>";
     
    $result .= "<div class='section-example-container'>
                    <pre class='csharp'>Log(\"My log message\");</pre>
                    <pre class='python'>self.Log(\"My log message\")</pre>
                </div>";
    
    echo $result;
}

?>





