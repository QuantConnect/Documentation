<?php

$getUniverseSettingsText = function($isFramework) {
    $result = "<p>The universe settings of your algorithm configure some properties of the universe constituents. The following table describes the properties of the <code>UniverseSettings</code> object:</p>

    <table class="qc-table table vertical-table">
    <tbody>
            <tr>
                <td>
                    <h4>Property: <span><code>ExtendedMarketHours</code></span></h4>
                    <p class="property-description">Should assets also feed extended market hours? You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
                    <p>Data Type: <span><code>bool</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>False</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>FillForward</code></span></h4>
                    <p class="property-description">Should asset data fill forward?</p>
                    <p>Data Type: <span><code>bool</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>True</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>MinimumTimeInUniverse</code></span></h4>
                    <p class="property-description">What's the minimum time assets should be in the universe?</p>
                    <p>Data Type: <span><code>timedelta</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>timedelta(1)</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>Resolution</code></span></h4>
                    <p class="property-description">What resolution should assets use?</p>
                    <p>Data Type: <span><code>Resolution</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>Resolution.Minute</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>ContractDepthOffset</code></span></h4>
                    <p class="property-description">What offset from the current front month should be used for <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts">continuous Future contracts</a>? 0 uses the front month and 1 uses the back month contract. This setting is only available for Future assets.</p>
                    <p>Data Type: <span><code>int</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>0</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>DataMappingMode</code></span></h4>
                    <p class="property-description">How should continuous Future contracts be mapped? This setting is only available for Future assets.</p>
                    <p>Data Type: <span><code>DataMappingMode</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>DataMappingMode.OpenInterest</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>DataNormalizationMode</code></span></h4>
                    <p class="property-description">How should historical prices be adjusted? This setting is only available for Equity and Futures assets.</p>
                    <p>Data Type: <span><code>DataNormalizationMode</code></span><span class="pipe-separator">  |  </span> Default Value: <span><code>DataNormalizationMode.Adjusted</code></span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>Property: <span><code>Leverage</code></span></h4>
                    <p class="property-description">What leverage should assets use in the universe? This setting is not available for derivative assets.</p>
                    <p>Data Type: <span><code>float</code></span><span  class="pipe-separator">  |  </span> Default Value: <span><code>Security.NullLeverage</code></span></p>
                </td>
            </tr>
    </tbody>
</table>


<p>To set the <code>UniverseSettings</code>, update the preceding properties in the <code>Initialize</code> method before you add the ";	
    if ($isFramework) 
    {
	$result .= "Universe Selection model";
    }
    else
    {
	$result .= "universe";
    }
    $result .= ". These settings are globals, so they apply to all universes you create.</p>";

  
    if ($isFramework) 
    {
        $result .= "
        <div class=\"section-example-container\">
<pre class=\"csharp\">// Request second resolution data. This will be slow!
UniverseSettings.Resolution = Resolution.Second;
AddUniverseSelection(new VolatilityETFUniverse());</pre>
<pre class=\"python\"># Request second resolution data. This will be slow!
self.UniverseSettings.Resolution = Resolution.Second
self.AddUniverseSelection(VolatilityETFUniverse())</pre>
</div>
";
    }
    else
    {
        $result .= "
        <div class=\"section-example-container\">
<pre class=\"csharp\">// Request second resolution data. This will be slow!
UniverseSettings.Resolution = Resolution.Second;
AddUniverse(MyCoarseFilterFunction);</pre>
<pre class=\"python\"># Request second resolution data. This will be slow!
self.UniverseSettings.Resolution = Resolution.Second
self.AddUniverse(self.MyCoarseFilterFunction)</pre>
</div>
";
    }
  
    echo $result;
}

?>
