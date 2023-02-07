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
    <li>Define the <a href="/docs/v2/lean-engine/contributions/datasets/key-concepts#04-Set-Data-Sources">GetSource</a> method to point to the path of your dataset file(s).</li>
    <p>Set the file name to the security ticker with <code>config.Symbol.Value</code>. An example output file path is <span class="public-file-name">/ output / alternative / xyzairline / ticketsales / dal.csv</span>.</p>
    <li>Define the <a href="/docs/v2/lean-engine/contributions/datasets/key-concepts#05-Parse-Custom-Data">Reader</a> method to return instances of your dataset class.</li>
    <p>Set <code>Symbol = config.Symbol</code> and set <code>EndTime</code> to the time that the datapoint first became available for consumption.</p>

    <?php 
    $classNameEnding = "";
    include(DOCS_RESOURCES."/lean-engine/contributions/datasets/define-methods.php"); 
    ?>
</ol>
