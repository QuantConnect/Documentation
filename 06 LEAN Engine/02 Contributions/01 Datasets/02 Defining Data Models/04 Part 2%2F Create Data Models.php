<p>The input to your model should be a <span class="public-file-name">CSV</span> file that's in chronological order.</p>

<div class="section-example-container"><pre>
1997-01-01,905.2,941.4,905.2,939.55,38948210,978.21
1997-01-02,941.95,944,925.05,927.05,49118380,1150.42
1997-01-03,924.3,932.6,919.55,931.65,35263845,866.74
...
2014-07-24,7796.25,7835.65,7771.65,7830.6,117608370,6271.45
2014-07-25,7828.2,7840.95,7748.6,7790.45,153936037,7827.61
2014-07-28,7792.9,7799.9,7722.65,7748.7,116534670,6107.78
</pre></div>

<p>Follow these steps to define the data source class:</p>

<ol>
    <li>Open the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;.cs</span> file.</li>
    <li>Follow these steps to define the properties of your dataset:</li>
    <ol>
        <li>Duplicate lines 32-36 for as many properties as there are in your dataset.</li>
        <li>Rename the <code>SomeCustomProperty</code> properties to the names of your dataset properties (for example, <code>Destination</code>).</li>
        <li>If your dataset is a streaming dataset like the <a href="https://www.quantconnect.com/datasets/benzinga-news-feed">Benzinga News Feed</a>, change the argument that is passed to the <code>ProtoMember</code> members so that they start at 10 and increment by one for each additional property in your dataset.</li>
        <li>If your dataset isn't a streaming dataset, delete the <code>ProtoMember</code> members.</li>
        <li>Replace the “Some custom data property” comments with a description of each property in your dataset.</li>
    </ol>
    <p>If your dataset contains multiple series, like the <a href='https://www.quantconnect.com/datasets/us-federal-reserve-economic-data'>FRED dataset</a>, create a helper class file in <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt;</span> directory to map the series name to the series code. For a full example, see the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean.DataSource.FRED/blob/master/LIBOR.cs'>LIBOR.cs file</a> in the Lean.DataSource.FRED repository. The helper class makes it easier for members to subscribe to the series in your dataset because they don't need to know the series code. For instance, you can subscribe to the 1-Week London Interbank Offered Rate (LIBOR) based on U.S. Dollars with the following code snippet:</p>

    <div class="section-example-container">
    <pre class='csharp'>AddData&lt;Fred&gt;(Fred.LIBOR.OneWeekBasedOnUSD);
// Instead of
// AddData&lt;Fred&gt;("USD1WKD156N");</pre>
    <pre class='python'>self.AddData(Fred, Fred.LIBOR.OneWeekBasedOnUSD)
# Instead of
# self.AddData(Fred, "USD1WKD156N")</pre>
    </div>

    <li>Define the <a href="/docs/v2/lean-engine/contributions/datasets/key-concepts#04-Data-Sources">GetSource</a> method to point to the path of your dataset file(s).</li>
    <p>Set the file name to the security ticker with <code>config.Symbol.Value</code>, which is the string value of the argument you pass to the <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#08-Add-Data'>AddData</a> method when you subscribe to the dataset. An example output file path is <span class="public-file-name">/ output / alternative / xyzairline / ticketsales / dal.csv</span>.</p>
    <li>Define the <code>Reader</code> method to return instances of your dataset class.</li>
    <p>Set <code>Symbol = config.Symbol</code> and set <code>EndTime</code> to the time that the datapoint first became available for consumption.</p>
    <p>Your data class inherits from the <code>BaseData</code> class, which has <code>Value</code> and <code>Time</code> properties. Set the <code>Value</code> property to one of the factors in your dataset. If you don't set the <code>Time</code> property, its default value is the value of <code>EndTime</code>. For more information about the <code>Time</code> and <code>EndTime</code> properties, see <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/time-modeling/periods'>Periods</a>.</p>

    <?php 
    $classNameEnding = "";
    include(DOCS_RESOURCES."/lean-engine/contributions/datasets/define-methods.php"); 
    ?>
</ol>
