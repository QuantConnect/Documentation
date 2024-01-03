<p>If your dataset doesn't provide universe data, follow these steps:</p>

<ol>
    <li>Delete the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;Universe.cs</span>.</li>
    <li>Delete the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;UniverseSelectionAlgorithm.*</span> files.</li>
    <li>In the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / tests / Tests.csproj</span> file, delete the code on line 8 that compiles the universe selection algorithms.</li>
    <li>Skip the rest of this page.</li>
</ol>

<p>The input to your model should be many <span class="public-file-name">CSV</span> files where the first column is the <a href='http://www.quantconnect.com/docs/v2/writing-algorithms/key-concepts/security-identifiers'>security identifier</a> and the second column is the point-in-time ticker.</p>

<div class="section-example-container"><pre>
A R735QTJ8XC9X,A,17.19,109700,1885743,False,0.9904858,1
AA R735QTJ8XC9X,AA,71.25,513400,36579750,False,0.3992678,0.750075
AAB R735QTJ8XC9X,AAB,16.38,5000,81900,False,0.9902758,1
...
ZSEV R735QTJ8XC9X,ZSEV,10.5,800,8400,False,0.8981684,1
ZTR R735QTJ8XC9X,ZTR,9.56,102300,977988,False,0.0803037,3.97015016
ZVX R735QTJ8XC9X,ZVX,10,15600,156000,False,1,0.666667
</pre></div>

<p>Follow these steps to define the data source class:</p>

<ol>
    <li>Open the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;Universe.cs</span> file.</li>
    <li>Follow these steps to define the properties of your dataset:</li>
    <ol>
        <li>Duplicate lines 33-36 or 38-41 (depending on the data type) for as many properties as there are in your dataset.</li>
        <li>Rename the <code>SomeCustomProperty</code>/<code>SomeNumericProperty</code> properties to the names of your dataset properties (for example, <code>Destination</code>/<code>FlightPassengerCount</code>).</li>
        <li>Replace the “Some custom data property” comments with a description of each property in your dataset.</li>
    </ol>
    <li>Define the <a href="/docs/v2/lean-engine/contributions/datasets/key-concepts#04-Data-Sources">GetSource</a> method to point to the path of your dataset file(s).</li>
    <p>Use the <code>date</code> parameter as the file name to get the <code>DateTime</code> of data being requested. Example output file paths are <span class="public-file-name">/ output / alternative / xyzairline / ticketsales / universe / 20200320.csv</span> for daily data and <span class="public-file-name">/ output / alternative / xyzairline / ticketsales / universe / 2020032000.csv</span> for hourly data.<br></p>
    <li>Define the <code>Reader</code> method to return instances of your universe class.</li>
    <p>The first column in your data file must be the security identifier and the second column must be the point-in-time ticker. With this configuration, use <code>new Symbol(SecurityIdentifier.Parse(csv[0]), csv[1])</code> to create the security <code>Symbol</code>.</p>
    <p>The date in your data file must be the date that the data point is available for consumption. With this configuration, set the <code>Time</code> to <code>date - Period</code>.</p>
    
    <?php 
    $classNameEnding = "Universe";
    include(DOCS_RESOURCES."/lean-engine/contributions/datasets/define-methods.php"); 
    ?>
</ol>
