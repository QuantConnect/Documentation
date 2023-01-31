<?
$keyText = $writingAlgorithms ? "To store data, you need to provide a key. If you provide a key that is already in the Object Store, it will overwrite the data at that location. To avoid overwriting objects from other projects in your organization, prefix the key with your project ID. You can find the project ID in the URL of your browser when you open a project. For example, the ID of the project at <span class='public-file-name'>quantconnect.com/project/12345</span> is 12345." : "";
$cSharpPrefix = $research ? "qb." : "";
$pythonPrefix = $research ? "qb." : "self.";
?>

<p>The Object Store saves objects under a key-value system. If you save objects in backtests, you can access them from the Research Environment. <?=$writingAlgorithmsText?></p>

<p>If you run algorithms in QuantConnect Cloud, you need <a href='/docs/v2/cloud-platform/organizations/members#08-Permissions'>storage create permissions</a> to save data in the Object Store.</p>

<p>If you don't have data to store, <a href='<?=$writingAlgorithms ? "/docs/v2/writing-algorithms/object-store#03-Create-Sample-Data" : "/docs/v2/research-environment/object-store#03-Create-Sample-Data" ?>'>create some sample data</a>.</p>

<p class='csharp'>You can save the following types of objects in the Object Store:</p>

<ul class='csharp'>
    <li><code>Bytes</code> objects</li>
    <li><code>string</code> objects</li>
    <li>JSON objects</li>
    <li>XML-formatted objects</li>
</ul>

<p class='csharp'><?=$keyText?></p>

<p class='python'>You can save <code>Bytes</code> and <code>string</code> objects in the Object Store. <?=$keyText?></p>

<h4>Bytes</h4>
<p>To save a <code>Bytes</code> object, call the <code>SaveBytes</code> method.</p>
<div class='section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.SaveBytes($"{<?=$cSharpPrefix?>ProjectId}/bytesKey", bytesSample)</pre>
    <pre class='python'>save_successful = <?=$pythonPrefix?>ObjectStore.SaveBytes(f"{<?=$pythonPrefix?>ProjectId}/bytes_key", bytes_sample)</pre>
</div>

<h4>Strings</h4>
<p>To save a <code>string</code> object, call the <code>Save</code> or <code>SaveString</code> method.</p>
<div class='section-example-container'>
    <pre class='csharp'>var saveSuccessful = <?=$cSharpPrefix?>ObjectStore.Save($"{<?=$cSharpPrefix?>ProjectId}/stringKey", stringSample);</pre>
    <pre class='python'>save_successful = <?=$pythonPrefix?>ObjectStore.Save(f"{<?=$pythonPrefix?>ProjectId}/string_key", string_sample)</pre>
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