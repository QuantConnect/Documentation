<?
$cSharpPrefix = $research ? "qb." : "";
$pythonPrefix = $research ? "qb." : "self.";
?>

<p>The Object Store saves objects under a key-value system. If you save objects in backtests, you can access them from the Research Environment. <?=$writingAlgorithms ? "To avoid slowing down your backtests, save data once in the <code class="csharp">OnEndOfAlgorithm</code><code class="python">on_end_of_algorithm</code> event handler. In live trading, you can save data more frequently like at the end of a <code>Train</code> method or after universe selection." : "" ?></p>

<p>If you run algorithms in QuantConnect Cloud, you need <a href='/docs/v2/cloud-platform/organizations/members#08-Permissions'>storage create permissions</a> to save data in the Object Store.</p>

<p>If you don't have data to store, <a href='<?=$writingAlgorithms ? "/docs/v2/writing-algorithms/object-store#03-Create-Sample-Data" : "/docs/v2/research-environment/object-store#03-Create-Sample-Data" ?>'>create some sample data</a>.</p>

<p class='csharp'>You can save the following types of objects in the Object Store:</p>
<ul class='csharp'>
    <li><code>Bytes</code> objects</li>
    <li><code>string</code> objects</li>
    <li>JSON objects</li>
    <li>XML-formatted objects</li>
</ul>

<p>
<span class='python'>You can save <code>Bytes</code> and <code>string</code> objects in the Object Store.</span>
<?=$writingAlgorithms ? "To store data, you need to provide a key. If you provide a key that is already in the Object Store, it will overwrite the data at that location. To avoid overwriting objects from other projects in your organization, prefix the key with your project ID. You can find the project ID in the URL of your browser when you open a project. For example, the ID of the project at <span class='public-file-name'>quantconnect.com/project/12345</span> is 12345." : ""?>
</p>

<h4>Strings</h4>
<p>To save a <code>string</code> object, call the <code class='csharp'>Save</code><code class='python'>save</code> or <code class='csharp'>SaveString</code><code class='python'>save_string</code> method.</p>
<div class='section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.Save($"{<?=$cSharpPrefix?>ProjectId}/stringKey", stringSample);</pre>
    <pre class='python'>save_successful = <?=$pythonPrefix?>object_store.save(f"{<?=$pythonPrefix?>project_id}/string_key", string_sample)</pre>
</div>

<h4 class='csharp'>JSON</h4>
<p class='csharp'>To save a JSON object, call the <code>SaveJson&lt;T&gt;</code> method. This method helps to serialize the data into JSON format.</p>
<div class='csharp section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.SaveJson&lt;Dictionary&lt;string, int&gt;&gt;($"{<?=$cSharpPrefix?>ProjectId}/jsonKey", dictSample);</pre>
</div>

<h4 class='csharp'>XML</h4>
<p class='csharp'>To save an XML-formatted object, call the <code>SaveXml&lt;T&gt;</code> method.</p>
<div class='csharp section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.SaveXml&lt;XElement&gt;($"{<?=$cSharpPrefix?>ProjectId}/xmlKey", xmlSample);</pre>
</div>

<h4>Bytes</h4>
<p>To save a <code>Bytes</code> object (for example, zipped data), call the <code class='csharp'>SaveBytes</code><code class='python'>save_bytes</code> method.</p>
<div class='section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.SaveBytes($"{<?=$cSharpPrefix?>ProjectId}/bytesKey", bytesSample)

var zippedDataSample = Compression.ZipBytes(Encoding.UTF8.GetBytes(stringSample), "data");
var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.SaveBytes($"{<?=$cSharpPrefix?>ProjectId}/bytesKey.zip", zippedDataSample);</pre>
    <pre class='python'>save_successful = <?=$pythonPrefix?>object_store.save_bytes(f"{<?=$pythonPrefix?>project_id}/bytes_key", bytes_sample)

zipped_data_sample = Compression.zip_bytes(bytes(string_sample, "utf-8"), "data")
zip_save_successful = <?=$pythonPrefix?>object_store.save_bytes(f"{<?=$pythonPrefix?>project_id}/bytesKey.zip", zipped_data_sample)</pre>
</div>
