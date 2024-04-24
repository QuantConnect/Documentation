<p><?=$writingAlgorithms ? "Read data from the Object Store to import algorithm variables between deployments, import data from the Research Environment, or load trained machine learning models. " : ""?>To read data from the Object Store, you need to provide the key you used to store the object.</p>
<?
$cSharpPrefix = $research ? "qb." : "";
$pythonPrefix = $research ? "qb." : "self.";
?>

<p class='csharp'>You can load the following types of objects from the Object Store:</p>

<ul class='csharp'>
    <li><code>Bytes</code> objects</li>
    <li><code>string</code> objects</li>
    <li>JSON objects</li>
    <li>XML-formatted objects</li>
</ul>

<p class='python'>You can load <code>Bytes</code> and <code>string</code> objects from the Object Store.</p>

<p>Before you read data from the Object Store, check if the key exists.</p>

<div class='section-example-container'>
    <pre class='csharp'>if (<?=$cSharpPrefix?>ObjectStore.ContainsKey(key))
{
    // Read data
}</pre>
    <pre class='python'>if <?=$pythonPrefix?>object_store.contains_key(key):
    # Read data</pre>
</div>


<h4>Strings</h4>

<p>To read a <code>string</code> object, call the <code class='csharp'>Read</code><code class='python'>read</code> or <code class='csharp'>ReadString</code><code class='python'>read_string</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>var stringData = <?=$cSharpPrefix?>ObjectStore.Read($"{<?=$cSharpPrefix?>ProjectId}/stringKey");</pre>
    <pre class='python'>string_data = <?=$pythonPrefix?>object_store.read(f"{<?=$pythonPrefix?>project_id}/string_key")</pre>
</div>

<h4 class='csharp'>JSON</h4>
<p class='csharp'>To read a JSON object, call the <code>ReadJson&lt;T&gt;</code> method.</p>
<div class='csharp section-example-container'>
    <pre class='csharp'>var jsonData = <?=$cSharpPrefix?>ObjectStore.ReadJson&lt;Dictionary&lt;string, int&gt;&gt;($"{<?=$cSharpPrefix?>ProjectId}/jsonKey");</pre>
</div>

<h4 class='csharp'>XML</h4>
<p class='csharp'>To read an XML-formatted object, call the <code>ReadXml&lt;T&gt;</code> method.</p>
<div class='csharp section-example-container'>
    <pre class='csharp'>var xmlData = <?=$cSharpPrefix?>ObjectStore.ReadXml&lt;XElement&gt;($"{<?=$cSharpPrefix?>ProjectId}/xmlKey");</pre>
</div>

<p class='csharp'>If you created the XML object from a dictionary, reconstruct the dictionary.</p>
<div class='csharp section-example-container'>
    <pre class='csharp'>var dict = xmlData.Elements().ToDictionary(x => x.Name.LocalName, x => int.Parse(x.Value));</pre>
</div>

<h4>Bytes</h4>

<p>To read a <code>Bytes</code> object, call the <code class='csharp'>ReadBytes</code><code class='python'>read_bytes</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>var bytesData = <?=$cSharpPrefix?>ObjectStore.ReadBytes($"{<?=$cSharpPrefix?>ProjectId}/bytesKey");</pre>
    <pre class='python'>byte_data = <?=$pythonPrefix?>object_store.read_bytes(f"{<?=$pythonPrefix?>project_id}/bytes_key")</pre>
</div>