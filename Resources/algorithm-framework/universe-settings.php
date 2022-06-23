<?php

$getUniverseSettingsText = function($isFramework) {
    $result = "<p>The universe settings of your algorithm configure some properties of the universe constituents. The following table describes the properties of the <code>UniverseSettings</code> object:</p>

<table class=\"qc-table table\">
    <thead>
        <tr>
            <th>Property</th>
	        <th>Data Type</th>
            <th>Description</th>
	        <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ExtendedMarketHours</code></td>
            <td><code>bool</code></td>
            <td>Should assets also feed extended market hours?</td>
            <td><code class='csharp'>false</code><code class='python'>False</code></td>
        </tr>
        <tr>
            <td><code>FillForward</code></td>
            <td><code>bool</code></td>
            <td>Should asset data fill forward?</td>
            <td><code class='csharp'>true</code><code class='python'>True</code></td>
        </tr>
        <tr>
            <td><code>MinimumTimeInUniverse</code></td>
            <td><code class='csharp'>TimeSpan</code><code class='python'>timedelta</code></td>
            <td>What's the minimum time assets should be in the universe?</td>
            <td><code class='csharp'>TimeSpan.FromDays(1)</code><code class='python'>timedelta(1)</code></td>
        </tr>
        <tr>
            <td><code>Resolution</code></td>
            <td><code>Resolution</code></td>
            <td>What resolution should assets use?</td>
            <td><code>Resolution.Minute</code></td>
        </tr>
	<tr>
            <td><code>ContractDepthOffset</code></td>
            <td><code>int</code></td>
            <td>What offset from the current front month should be used for <a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#05-Continuous-Contracts'>continuous Future contracts</a>? 0 uses the front month and 1 uses the back month contract. This setting is only available for Future assets.</td>
            <td>0</td>
        </tr>
	<tr>
            <td><code>DataMappingMode</code></td>
            <td><code>DataMappingMode</code></td>
            <td>How should continuous Future contracts be mapped? This setting is only available for Future assets.</td>
            <td><code>DataMappingMode.OpenInterest</code></td>
        </tr>
	<tr>
            <td><code>DataNormalizationMode</code></td>
            <td><code>DataNormalizationMode</code></td>
            <td>How should historical prices be adjusted? This setting is only available for Equity and Futures assets.</td>
            <td><code>DataNormalizationMode.Adjusted</code></td>
        </tr>
        <tr>
            <td><code>Leverage</code></td>
            <td><code class='csharp'>decimal</code><code class='python'>float</code></td>
            <td>What leverage should assets use in the universe? This setting is not available for derivative assets.</td>
            <td><code>Security.NullLeverage</code></td>
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
