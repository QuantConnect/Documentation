<?php
$getHistoricalValuesText = function($linkToIndicatorsPage) {
    $result = "
<p>To track historical indicator values, use a <code>RollingWindow</code>. Indicators emit an <code>Updated</code> event when they update. To create a <code>RollingWindow</code> of indicator points, attach an event handler function to the <code>Updated</code> member that adds the last value of the indicator to the <code>RollingWindow</code>. The value is an <code>IndicatorDataPoint</code> object that represents a piece of data at a specific time.</p>
<div class=\"section-example-container\">
<pre class=\"csharp\">public override void Initialize()
{
   // Create an indicator and adds to a RollingWindow when it is updated
   smaWindow = new RollingWindow&lt;IndicatorDataPoint&gt;(5);
   SMA(\"SPY\", 5).Updated += (sender, updated) =&gt; smaWindow.Add(updated);
}
</pre> 
	<pre class=\"python\">def Initialize(self):
    # Creates an indicator and adds to a RollingWindow when it is updated
    self.sma_window = RollingWindow[IndicatorDataPoint](5)
    self.SMA(\"SPY\", 5).Updated += (lambda sender, updated: self.sma_window.Add(updated))</pre>
</div>";
  
    if ($linkToIndicatorsPage)
    {
        $result .= "
<p>To view how to access individual members in an indicator, see <a href=\"/docs/v2/writing-algorithms/indicators/key-concepts#08-Get-Indicator-Values\">Get Indicator Values</a>.</p>

<p>The current (most recent) indicator value is at index 0, the previous value is at index 1, and so on until the length of the window.</p>        

<div class=\"section-example-container\">
	<pre class=\"csharp\">var currentSma = smaWin[0];
var previousSma = smaWin[1];
var oldestSma = smaWin[smaWin.Count - 1];</pre>
	<pre class=\"python\">current_sma = self.sma_window[0]
previous_sma = self.sma_window[1]
oldest_sma = self.sma_window[sma_window.Count - 1]</pre>
</div>";   
    }
	
    echo $result;
}
?>
