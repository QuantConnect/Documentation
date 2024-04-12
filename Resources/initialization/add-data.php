<? $location = $writingAlgorithms ? "algorithms" : "notebooks"; ?>

<p>You can subscribe to asset, fundamental, alternative, and custom data. The <a href='https://www.quantconnect.com/datasets'>Dataset Market</a> provides <?=$kpi["dataset-size"] ?> of data that you can easily import into your <?=$location?>.</p>

<h4>Asset Data</h4>

<p>To subscribe to asset data, call one of the asset subscription methods like <code>AddEquity</code> or <code>AddForex</code>. Each asset class has its own method to create subscriptions. For more information about how to create subscriptions for each asset class, see <?=$writingAlgorithms ? "<a href='/docs/v2/writing-algorithms/securities/asset-classes'>Asset Classes</a>" : "the <span class='page-section-name'>Create Subscriptions</span> section of an asset class in the <a href='/docs/v2/research-environment/datasets/key-concepts'>Datasets</a> chapter" ?>.</p>

<div class='section-example-container'>
	<pre class='csharp'><?=$writingAlgorithms ? "" : "qb."?>AddEquity("AAPL"); // Add Apple 1 minute bars (minute by default)
<?=$writingAlgorithms ? "" : "qb."?>AddForex("EURUSD", Resolution.Second); // Add EURUSD 1 second bars
</pre>
	<pre class='python'><?=$writingAlgorithms ? "self" : "qb"?>.add_equity("SPY")  # Add Apple 1 minute bars (minute by default)
<?=$writingAlgorithms ? "self" : "qb"?>.add_forex("EURUSD", Resolution.SECOND) # Add EURUSD 1 second bars
</pre>
</div>

<? if ($writingAlgorithms) { ?>
    <p>In live trading, you define the securities you want, but LEAN also gets the securities in your live portfolio and sets their resolution to the lowest resolution of the subscriptions you made. For example, if you create subscriptions in your algorithm for securities with Second, Minute, and Hour resolutions, the assets in your live portfolio are given a resolution of Second.</p>
<? } ?>

<h4>Alternative Data</h4>

<p>
To add alternative datasets to your <?=$location?>, call the <code>AddData</code> method. <? if ($writingAlgorithms) { ?> For full examples, in the <a href='/docs/v2/writing-algorithms/datasets/overview'>Datasets</a> chapter, select a dataset and see the <span class='page-section-name'>Requesting Data</span> section.
<? } else { ?> For a full example, see <a href='/docs/v2/research-environment/datasets/alternative-data'>Alternative Data</a>. <? } ?>
</p>
 
<h4>Custom Data</h4>

<p>To add custom data to your <?=$location?>, call the <code>AddData</code> method. For more information about custom data, see <?=$writingAlgorithms ? "<a href='/docs/v2/writing-algorithms/importing-data/key-concepts'>Importing Data</a>" : "<a href='/docs/v2/research-environment/datasets/custom-data'>Custom Data</a>"?>.</p>    

<h4>Limitations</h4>
<p>There is no official limit to how much data you can add to your <?=$location?>, but there are practical resource limitations. Each security subscription requires about 5MB of RAM, so larger machines let you <?=$writingAlgorithms ? "run algorithms with bigger universes" : "request more data"?>. For more information about our cloud nodes, see <?=$writingAlgorithms ? "<a href='/docs/v2/cloud-platform/organizations/resources'>Resources</a>" : "<a href='/docs/v2/cloud-platform/organizations/resources#03-Research-Nodes'>Research Nodes</a>"?>.</p>
