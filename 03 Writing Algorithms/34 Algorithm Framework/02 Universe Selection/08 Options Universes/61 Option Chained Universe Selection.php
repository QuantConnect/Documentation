<p>An Option chained universe subscribes to Option contracts on the constituents of a <a href="/docs/v2/writing-algorithms/universes/equity">US Equity universe</a>. <br>

</p><div class="section-example-container">
	<pre class="csharp">// Subscribe price data unadjusted for splits and dividends ("raw") into the algorithm. Required for options and useful for more accurately modeling historical periods.
UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
UniverseSettings.Asynchronous = true;
AddUniverseSelection(
    new OptionChainedUniverseSelectionModel(
	// Add 10 highest dollar trading volume securities to the universe.
        AddUniverse(Universe.DollarVolume.Top(10)),
	// Set contract filter to return only front month CALL options that have the strike price within 2 strike level.
        optionFilterUniverse => optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly()
    )
);</pre>
	<pre class="python"># Subscribe price data unadjusted for splits and dividends ("raw") into the algorithm. Required for options and useful for more accurately modeling historical periods.
self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
self.universe_settings.asynchronous = True
self.add_universe_selection(
    OptionChainedUniverseSelectionModel(
	# Add 10 highest dollar trading volume securities to the universe.
        self.add_universe(self.universe.dollar_volume.top(10)),
	# Set contract filter to return only front month CALL options that have the strike price within 2 strike level.
        lambda option_filter_universe: option_filter_universe.strikes(-2, +2).front_month().calls_only()
    )
)</pre>

</div>


<p>The following table describes the arguments the model accepts:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>universe</code></td>
	    <td><code>Universe</code></td>
            <td>The universe to chain onto the Option Universe Selection model</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">optionFilter</code><code class="python">option_filter</code></td>
	    <td><code class="csharp">Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt;</code><code class="python">Callable[[OptionFilterUniverse], OptionFilterUniverse]</code></td>
            <td>The Option filter universe to use</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">universeSettings</code><code class="python">universe_settings</code></td>
	    <td><code>UniverseSettings</code></td>
            <td>The <a href="/docs/v2/writing-algorithms/algorithm-framework/universe-selection/universe-settings">universe settings</a>. If you don't provide an argument, the model uses the <code class="csharp">algorithm.UniverseSettings</code><code class="python">algorithm.universe_settings</code> by default.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>The <code class="csharp">optionFilter</code><code class="python">option_filter</code> function receives and returns an <code>OptionFilterUniverse</code> to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>

<? include(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>The following example shows how to define the Option filter as an isolated method:</p>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
    // Setup algorithm settings and request data.
    UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
    UniverseSettings.Asynchronous = true;
    AddUniverseSelection(
        new OptionChainedUniverseSelectionModel(
            AddUniverse(Universe.DollarVolume.Top(10)), OptionFilterFunction
        )
    );
}

// Set contract filter to return only front month CALL options that have the strike price within 2 strike level.
private OptionFilterUniverse OptionFilterFunction(OptionFilterUniverse optionFilterUniverse)
{
    return optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly();
}</pre>
	<pre class="python">def initialize(self) -&gt; None:
    # Setup algorithm settings and request data.
    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
    self.universe_settings.asynchronous = True
    self.add_universe_selection(
        OptionChainedUniverseSelectionModel(
            self.add_universe(self.universe.dollar_volume.top(10)), self.option_filter_function
        )
    )

# Set contract filter to return only front month CALL options that have the strike price within 2 strike level.
def option_filter_function(self, option_filter_universe: OptionFilterUniverse) -&gt; OptionFilterUniverse:
    return option_filter_universe.strikes(-2, +2).front_month().calls_only()</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm/Selection/OptionChainedUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
