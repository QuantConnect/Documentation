<p>An Option chained universe subscribes to Option contracts on the constituents of a <a href="/docs/v2/writing-algorithms/universes/equity">US Equity universe</a>. <br>

</p><div class="section-example-container">
	<pre class="csharp">UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
UniverseSettings.Asynchronous = true;
AddUniverseSelection(
    new OptionChainedUniverseSelectionModel(
        AddUniverse(Universe.DollarVolume.Top(10)),
        optionFilterUniverse => optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly()
    )
);</pre>
	<pre class="python">self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
self.universe_settings.Asynchronous = True
self.add_universe_selection(
    OptionChainedUniverseSelectionModel(
        self.add_universe(self.universe.dollar_volume.top(10)),
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
            <td>The <a href="/docs/v2/writing-algorithms/algorithm-framework/universe-selection/universe-settings">universe settings</a>. If you don't provide an argument, the model uses the <code>algorithm.UniverseSettings</code> by default.</td>
            <td><code class='python'>None</code><code class="csharp">null</code></td>
        </tr>
    </tbody>
</table>

<p>The <code class="csharp">optionFilter</code><code class="python">option_filter</code> function receives and returns an <code>OptionFilterUniverse</code> to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>

<? include(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>The following example shows how to define the Option filter as an isolated method:</p>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
    UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
    UniverseSettings.Asynchronous = true;
    AddUniverseSelection(
        new OptionChainedUniverseSelectionModel(
            AddUniverse(Universe.DollarVolume.Top(10)), OptionFilterFunction
        )
    );
}

private OptionFilterUniverse OptionFilterFunction(OptionFilterUniverse optionFilterUniverse)
{
    return optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly();
}</pre>
	<pre class="python">def initialize(self) -&gt; None:
    self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
    self.universe_settings.Asynchronous = True
    self.add_universe_selection(
        OptionChainedUniverseSelectionModel(
            self.add_universe(self.universe.dollar_volume.top(10)), self.option_filter_function
        )
    )

def option_filter_function(self, option_filter_universe: OptionFilterUniverse) -&gt; OptionFilterUniverse:
    return option_filter_universe.strikes(-2, +2).front_month().calls_only()</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm/Selection/OptionChainedUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
