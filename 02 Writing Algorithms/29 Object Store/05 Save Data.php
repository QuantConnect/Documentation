<p>The Object Store saves objects under a key-value system. If you save objects in backtests, you can access them from the Research Environment. To avoid slowing down your backtests, save data once in the <code>OnEndOfAlgorithm</code> event handler. In live trading, you can save data more frequently like at the end of a <code>Train</code> method or after universe selection.</p>

<p>If you run algorithms in QC Cloud, you need <a href='/docs/v2/our-platform/organizations/members#06-Permissions'>storage create permissions</a> to save data in the Object Store.</p>

<p>If you don't have data to store, <a href='/docs/v2/writing-algorithms/object-store#03-Create-Sample-Data'>create some sample data</a>.</p>

<p class='csharp'>You can save the following types of objects in the Object Store:</p>

<ul class='csharp'>
    <li><code>Bytes</code> objects</li>
    <li><code>string</code> objects</li>
    <li>JSON objects</li>
    <li>XML-formatted objects</li>
</ul>

<p class='python'>You can save <code>Bytes</code> and <code>string</code> objects in the Object Store.</p>

<h4>Bytes</h4>
<p>To save a <code>Bytes</code> object, call the <code>SaveBytes</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">var saveSuccessful = ObjectStore.SaveBytes("bytesKey", bytesSample)</pre>
    <pre class="python">save_successful = self.ObjectStore.SaveBytes("bytes_key", bytes_sample)</pre>
</div>

<h4>Strings</h4>
<p>To save a <code>string</code> object, call the <code>Save</code> or <code>SaveString</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">var saveSuccessful = ObjectStore.Save("stringKey", stringSample);</pre>
    <pre class="python">save_successful = self.ObjectStore.Save("string_key", string_sample)</pre>
</div>

<h4 class="csharp">JSON</h4>
<p class="csharp">To save a JSON object, call the <code>SaveJson&lt;T&gt;</code> method. This method helps to serialize the data into JSON format.</p>
<div class="csharp section-example-container">
    <pre class="csharp">var saveSuccessful = ObjectStore&lt;Dictionary&lt;string, int&gt;&gt;.SaveJson("jsonKey", dictSample);</pre>
</div>

<h4 class="csharp">XML</h4>
<p class="csharp">To save an XML-formatted object, call the <code>SaveXml&lt;T&gt;</code> method.</p>
<div class="csharp section-example-container">
    <pre class="csharp">var saveSuccessful = ObjectStore.SaveXml("xmlKey", xmlSample);</pre>
</div>


