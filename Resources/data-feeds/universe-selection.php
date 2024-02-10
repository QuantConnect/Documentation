<p><a href='/docs/v2/writing-algorithms/universes/key-concepts'>Universe selection</a> <?=$availability ? "is" : "isn't"?> available with the <?=$dataFeedName?> data provider.</p>
<? if ($availability) { ?>
<div class='section-example-container'>
    <pre class='csharp'>UniverseSettings.Asynchronous = true;
AddUniverse(FundamentalUniverseSelection);</pre>
    <pre class='python'>self.UniverseSettings.Asynchronous = True
self.AddUniverse(self.FundamentalUniverseSelection)</pre>
</div> <?}?>