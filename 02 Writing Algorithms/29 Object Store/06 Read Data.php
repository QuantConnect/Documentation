<p>Read data from the Object Store to import algorithm variables between deployments, import data from the Research Environment, or load trained machine learning models. To read data from the Object Store, you need to provide the key you used to store the object.</p>

<p class='csharp'>You can load the following types of objects from the Object Store:</p>

<ul class='csharp'>
    <li><code>Bytes</code> objects</li>
    <li><code>string</code> objects</li>
    <li>JSON objects</li>
    <li>XML-formatted objects</li>
</ul>

<p class='python'>You can load <code>Bytes</code> and <code>string</code> objects from the Object Store.</p>

<p>Before you read data from the Object Store, check if the key exists.</p>

<div class="section-example-container">
    <pre class="csharp">if (ObjectStore.ContainsKey("key"))
{
    // Read data
}</pre>
    <pre class="python">if self.ObjectStore.ContainsKey("key"):
    # Read data</pre>
</div>


<h4>Bytes</h4>

<p>To read a <code>Bytes</code> object, call the <code>ReadBytes</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var bytesData = ObjectStore.Read("bytesKey");</pre>
    <pre class="python">byte_data = self.ObjectStore.Read("bytes_key")</pre>
</div>

<h4>Strings</h4>

<p>To read a <code>string</code> object, call the <code>Read</code> or <code>ReadString</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var stringData = ObjectStore.Read("stringKey");</pre>
    <pre class="python">string_data = self.ObjectStore.Read("string_key")</pre>
</div>

<h4 class="csharp">JSON</h4>
<p class="csharp">To read a JSON object, call the <code>ReadJson&lt;T&gt;</code> method.</p>
<div class="csharp section-example-container">
    <pre class="csharp">var jsonData = ObjectStore.ReadJson&lt;Dictionary&lt;string, int&gt;&gt;("jsonKey");</pre>
</div>

<h4 class="csharp">XML</h4>
<p class="csharp">To read an XML-formatted object, call the <code>ReadXml&lt;T&gt;</code> method.</p>
<div class="csharp section-example-container">
    <pre class="csharp">var xmlData = objectStore.ReadXml&lt;XElement&gt;("xmlKey");</pre>
</div>

<p class="csharp">If you created the XML object from a dictionary, reconstruct the dictionary.</p>
<div class="csharp section-example-container">
    <pre class="csharp">var dict = xmlData.Elements().ToDictionary(x => x.Name.LocalName, x => int.Parse(x.Value));</pre>
</div>