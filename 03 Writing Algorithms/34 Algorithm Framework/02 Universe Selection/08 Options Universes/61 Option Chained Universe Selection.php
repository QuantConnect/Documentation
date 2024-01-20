<p>An Option chained universe subscribes to Option contracts on the constituents of a <a href="/docs/v2/writing-algorithms/universes/equity">US Equity universe</a>. <br>

</p><div class="section-example-container">
	<pre class="csharp">UniverseSettings.Asynchronous = true;
AddUniverseOptions(universe, optionFilter);
</pre>
	<pre class="python">self.UniverseSettings.Asynchronous = True
self.AddUniverseOptions(universe, optionFilter)</pre>
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
            <td><code>optionFilter</code></td>
	    <td><code class="csharp">Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt;</code><code class="python">Callable[[OptionFilterUniverse], OptionFilterUniverse]</code></td>
            <td>The Option filter universe to use</td>
            <td></td>
        </tr>
    </tbody>
</table>

<p>The <code>optionFilter</code> function receives and returns an <code>OptionFilterUniverse</code> to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>


<? include(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
    UniverseSettings.Asynchronous = true;
    var universe = AddUniverse(Universe.DollarVolume.Top(10));
    AddUniverseOptions(universe, OptionFilterFunction);
}

private OptionFilterUniverse OptionFilterFunction(OptionFilterUniverse optionFilterUniverse)
{
    return optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly();
}</pre>
	<pre class="python">def Initialize(self) -&gt; None:
    self.UniverseSettings.Asynchronous = True
    universe = self.AddUniverse(self.Universe.DollarVolume.Top(10))
    self.AddUniverseOptions(universe, self.OptionFilterFunction)

def OptionFilterFunction(self, option_filter_universe: OptionFilterUniverse) -&gt; OptionFilterUniverse:
    return option_filter_universe.Strikes(-2, +2).FrontMonth().CallsOnly()</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>To override the default <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>pricing model</a> of the Options, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a> in a security initializer.</p>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm/Selection/OptionChainedUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
