<p><a href='/docs/v2/writing-algorithms/universes/key-concepts'>Universe selection</a> <?=$availability ? "is" : "isn't"?> available with the <?=$dataFeedName?> data feed.</p>
<? if ($availability) { ?>
<div class='section-example-container'>
    <pre class='csharp'>AddUniverse(FundamentalUniverseSelection);</pre>
    <pre class='python'>self.AddUniverse(self.FundamentalUniverseSelection)</pre>
</div> <?}?>