<?php

$getUniverseSettingsText = function($isFramework) {
    $result = "<p>The universe settings of your algorithm configures some properties of the universe constituents. The following table describes the <code>UniverseSettings</code> object:</p>

<table class=\"qc-table table\">
    <thead>
        <tr>
            <th>Property</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
	<tr>
            <td><code>UniverseSettings.ContractDepthOffset</code></td>
	    <td>What offset from the current front month should be used for continous contracts? 0 (default) uses the front month and 1 uses the back month contract.</td>
        </tr>
	<tr>
            <td><code>UniverseSettings.DataMappingMode</code></td>
	    <td>How should continuous Future contracts be mapped?</td>
        </tr>
	<tr>
            <td><code>UniverseSettings.DataNormalizationMode</code></td>
	    <td>How should historical Equity prices be adjusted?</td>
        </tr>
        <tr>
            <td><code>UniverseSettings.ExtendedMarketHours</code></td>
	    <td>Should assets also feed extended market hours?</td>
        </tr>
        <tr>
            <td><code>UniverseSettings.FillForward</code></td>
	    <td>Should asset data fill forward?</td>
        </tr>
        <tr>
            <td><code>UniverseSettings.Leverage</code></td>
	    <td>What leverage should assets use in the universe?</td>
        </tr>
        <tr>
            <td><code>UniverseSettings.MinimumTimeInUniverse</code></td>
	    <td>What's the minimum time assets should be in universe?</td>
        </tr>
        <tr>
            <td><code>UniverseSettings.Resolution</code></td>
	    <td>What resolution should added assets use?</td>
        </tr>
    </tbody>
</table>


<p>To set the <code>UniverseSettings</code>, update the preceding properties in the <code>Initialize</code> method before you add the universe. These settings are globals, so they apply to all universes you create.</p>
";	
  
    if ($isFramework) 
    {
        $result .= "
        <div class=\"section-example-container\">
<pre class=\"csharp\">// Request second resolution data. This will be slow!
UniverseSettings.Resolution = Resolution.Second;
.AddUniverseSelection(new VolatilityETFUniverse());</pre>
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
AddUniverse(MySecondResolutionCoarseFilterFunction);</pre>
<pre class=\"python\"># Request second resolution data. This will be slow!
self.UniverseSettings.Resolution = Resolution.Second
self.AddUniverse(self.MySecondResolutionCoarseFilterFunction)</pre>
</div>
";
    }
  
    echo $result;
}

?>
