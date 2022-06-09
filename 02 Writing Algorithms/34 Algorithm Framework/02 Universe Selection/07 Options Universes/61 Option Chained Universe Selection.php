<p>An Option chained universe subscribes to Option contracts on the constituents of a <a href="https://www.quantconnect.com/docs/v2/writing-algorithms/universes/equity">US Equity universe</a>. <br>

</p><div class="section-example-container">
	<pre class="csharp">AddUniverseOptions(universe, optionFilter);
</pre>
	<pre class="python">self.AddUniverseOptions(universe, optionFilter)</pre>
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
	    <td><code>Func&lt;OptionFilterUniverse, OptionFilterUniverse&lt;Symbol&gt;&gt;</code></td>
            <td>The Option filter universe to use</td>
            <td></td>
        </tr>
    </tbody>
</table>

<p>The <code>optionFilter</code> function receives and returns an <code>OptionFilterUniverse</code> to select the Option contracts. The following table describes the methods of the <code>OptionFilterUniverse</code> class:</p>


<?php echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
    var universe = AddUniverse(Universe.DollarVolume.Top(10));
    AddUniverseOptions(universe, OptionFilterFunction);
}

private OptionFilterUniverse OptionFilterFunction(OptionFilterUniverse optionFilterUniverse)
{
    return optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly();
}</pre>
	<pre class="python">def Initialize(self):
    universe = self.AddUniverse(self.Universe.DollarVolume.Top(10))
    self.AddUniverseOptions(universe, self.OptionFilterFunction)

def OptionFilterFunction(self, option_filter_universe):
    return option_filter_universe.Strikes(-2, +2).FrontMonth().CallsOnly()</pre>
</div>


<p>To view the implementation of this model, see the <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm/Selection/OptionChainedUniverseSelectionModel.cs">LEAN GitHub repository</a>.</p>
