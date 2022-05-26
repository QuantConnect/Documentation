<p>You need some data to store data in the Object Store.</p>

<p>Follow these steps to create some sample data:</p>

<ol>
    <li>Create a dictionary.</li>
    <div class="section-example-container">
        <pre class="csharp">var sample = new Dictionary&lt;string, int&gt; { {"One", 1}, {"Two", 2}, {"Three", 3} };</pre>
        <pre class="python">sample = {"One": 1, "Two": 2, "Three": 3}</pre>
    </div>

    <li>Convert the dictionary to a <code>string</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var stringSample = string.Join(Environment.NewLine, sample);</pre>
        <pre class="python">string_sample = str(sample)</pre>
    </div>

    <li>Convert the string to a <code>Bytes</code> object.</li>
    <div class="section-example-container">
        <pre class="csharp">var bytesSample = Encoding.UTF8.GetBytes(stringSample);</pre>
        <pre class="python">import json
bytes_sample = json.dumps(string_sample).encode('utf-8')</pre>
    </div>

    <li class="csharp">Convert the dictionary to an <code>XML</code>-formatted object.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">var xmlSample = new XElement("sample",
    sample.Select(kvp => new XElement(kvp.Key, kvp.Value)));
Log(xmlSample.ToString());</pre>
    </div>
    <img class="csharp" src="https://cdn.quantconnect.com/i/tu/store-data-xml-cs.png">
</ol>