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
<ol>
    <p>Call <code>ReadBytes</code> method to read data from a stored bytes object.</p>
    <div class="section-example-container">
        <pre class="csharp">var bytesData = objectStore.Read("saveBytes");</pre>
        <pre class="python">byte_data = object_store.Read("save_bytes")</pre>
    </div>

    <li class="csharp">Convert to string.</li>
    <li class="python">Call <code>json.loads</code> to restore the data string.</li>
    <div class="section-example-container">
        <pre class="csharp">var stringData = Encoding.UTF8.GetString(bytesData);</pre>
        <pre class="python">string_data = json.loads(bytearray(byte_data).decode('utf-8'))</pre>
    </div>

    <li>Restore as dictionary.</li>
    <div class="section-example-container">
        <pre class="csharp">var dict = stringFromBytes.Split(new[] {'\n'}, StringSplitOptions.RemoveEmptyEntries)
               .Select(part => part.Split('[')[1]).Select(part => part.Split(']')[0]).Select(part => part.Split(','))
               .ToDictionary(split => split[0], split => split[1]);</pre>
        <pre class="python">recovered_dict = eval(string_data)
recovered_dict, type(recovered_dict)</pre>
    </div>
    <img class="csharp" src="https://cdn.quantconnect.com/i/tu/storing-cs-6.png">
    <img class="python" src="https://cdn.quantconnect.com/i/tu/storing-py-4.png">
</ol>

<h4>Strings</h4>
<ol>
    <p>Call <code>Read</code> or <code>ReadString</code> method to read the stored string object.</p>
    <div class="section-example-container">
        <pre class="csharp">var stringData = objectStore.Read("saveString");</pre>
        <pre class="python">string_data = object_store.Read("save_string")</pre>
    </div>

    <li>Restore as dictionary.</li>
    <div class="section-example-container">
        <pre class="csharp">var dict = stringData.Split(new[] {'\n'}, StringSplitOptions.RemoveEmptyEntries)
               .Select(part => part.Split('[')[1]).Select(part => part.Split(']')[0]).Select(part => part.Split(','))
               .ToDictionary(split => split[0], split => split[1]);</pre>
        <pre class="python">recovered_dict = eval(string_data)
recovered_dict, type(recovered_dict)</pre>
    </div>
    <img class="csharp" src="https://cdn.quantconnect.com/i/tu/storing-cs-16.png">
    <img class="python" src="https://cdn.quantconnect.com/i/tu/storing-py-6.png">
</ol>

<h4 class="csharp">JSON</h4>
<ol class="csharp">
    <li class="csharp">Call the <code>ReadJson&lt;T&gt;</code> method to read the stored JSON object.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">var jsonData = objectStore.ReadJson&lt;Dictionary&lt;string, int&gt;&gt;("saveJson");</pre>
    </div>
    <img class="csharp" src="https://cdn.quantconnect.com/i/tu/storing-cs-10.png">
</ol>

<h4 class="csharp">XML</h4>
<ol class="csharp">
    <li class="csharp">Call the <code>ReadXml&lt;T&gt;</code> method to read the stored XML object.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">var xmlData = objectStore.ReadXml&lt;XElement&gt;("saveXml");</pre>
    </div>

    <li class="csharp">Reconstruct as a dictionary.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">var dict = xmlData.Elements().ToDictionary(x => x.Name.LocalName, x => int.Parse(x.Value));</pre>
    </div>
    <img class="csharp" src="https://cdn.quantconnect.com/i/tu/storing-cs-12.png">
</ol>
