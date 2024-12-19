<?
$imgLink = "";
$dataTypeLink = "/docs/v2/writing-algorithms/universes/equity";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>universe data</a>, call the <code>History</code> method with the <code>Universe</code> object.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>history</code> method with the <code>Universe</code> object.
  This method returns a DataFrame with columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">// Add the universe and save a reference to the Universe object.
var universe = AddUniverse(Universe.DollarVolume.Top(3));
// Get the historical universe data over the last 30 days.
var history = History(universe, TimeSpan.FromDays(30));</pre>
    <pre class="python"># Add the universe and save a reference to the Universe object.
// Get the historical universe data over the last 30 days in DataFrame format.
history = self.history(universe, timedelta(30), flatten=True)</pre>
</div>

<b>TODO:</b>
<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of universe data for an asset.'>

<p class='python'>To get the data in the format of the objects that you receive in your universe filter function instead of a DataFrame, use <code>flatten=False</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical universe data over the last 30 days in a Series where
# the values in the series are lists of the universe selection objects.
history = self.history(universe, timedelta(30), flatten=False)</pre>
</div>
