<p>Follow these steps to define the data source class:</p>

<ol>
    <li>If your dataset doesn't provide trading data, delete the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;.cs</span> file. If your dataset doesn't provide universe selection data, delete the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;Universe.cs</span> file.</li>
    <li>Open the <span class="public-file-name">Lean.DataSource.&lt;vendorNameDatasetName&gt; / &lt;vendorNameDatasetName&gt;Universe.cs</span> file.</li>
    <li>Follow these steps to define the properties of your dataset:</li>
    <ol>
        <li>Duplicate lines 33-36 or 38-41 (depending on the data type) for as many properties as there are in your dataset.</li>
        <li>Rename the <code>SomeCustomProperty</code>/<code>SomeNumericProperty</code> properties to the names of your dataset properties (for example, <code>Destination</code>/<code>FlightPassengerCount</code>).</li>
        <li>Replace the “Some custom data property” comments with a description of each property in your dataset.</li>
    </ol>
    <li>Define the <a href="/docs/v2/lean-engine/contributing-datasets/key-concepts#04-Set-Data-Sources">GetSource</a> method to point to the path of your dataset file(s).</li>
    <p>Use the <code style="font-size: 15px; background-color: rgb(255, 255, 255);">date</code> parameter as the file name to get the date of data being requested. An example output file path is <span class="public-file-name">/ output / alternative / xyzairline / ticketsales / universe / 20200320.csv</span>.<br></p>
    <li>Define the <a href="/docs/v2/lean-engine/contributing-datasets/key-concepts#05-Parse-Custom-Data">Reader</a> method to return instances of your universe class.</li>
    <p>The first column in your data file must be the security identifier and the second column must be the point-in-time ticker. With this configuration, use <code>new Symbol(SecurityIdentifier.Parse(csv[0]), csv[1])</code> to create the security <code>Symbol</code>.</p>
    <p>The date in your data file must be the date that the data point is available for consumption. With this configuration, set the <code>Time</code> to <code>date - Period</code>.</p>
    
    <?php 
    $classNameEnding = "Universe";
    include(DOCS_RESOURCES."/lean-engine/contributing-datasets/define-methods.php"); 
    ?>
</ol>
