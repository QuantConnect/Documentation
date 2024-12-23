<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-universe-dataframe-history.png";
?>

<p class='csharp'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>History</code> method with the <code>Universe</code> object.
</p>

<p class='python'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>history</code> method with the <code>Universe</code> object.
  <!-- Set the <code>flatten</code> argument to <code>True</code> to get a DataFrame that has columns for the data point attributes. -->
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the historical universe data over the last 5 days.
var history = History(universe, TimeSpan.FromDays(5));</pre>
    <pre class="python"># Get the historical universe data over the last 5 days in DataFrame format.
history = qb.history(universe, timedelta(5))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of universe data for a custom dataset.'>

<!-- 
<p class='python'>To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 30 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(30), flatten=False)</pre>
</div>
-->
