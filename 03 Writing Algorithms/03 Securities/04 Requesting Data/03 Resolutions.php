<?
include(DOCS_RESOURCES."/datasets/resolutions.php");
?>

<p>To set the resolution for all securities, set the <code>Resolution</code> <a href='/docs/v2/writing-algorithms/universes/settings#02-Properties'>universe setting</a> before you create security subscriptions.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Resolution = Resolution.Daily;</pre>
    <pre class="python">self.UniverseSettings.Resolution = Resolution.Daily</pre>
</div>

<p>To see which resolutions of data are available for a dataset, see the dataset listing in the <a href="/datasets">Data Market</a>. To create custom resolution periods, see <a href='/docs/v2/writing-algorithms/consolidating-data/getting-started'>Consolidating Data</a>.</p>

<p><span class='new-term'>Data density</span> describes the frequency of entries in a dataset. Datasets at the tick resolution have dense data density. All other resolutions usually have regular data density. If a non-tick resolution dataset doesn't have an entry at each sampling, it has sparse density.</p>
