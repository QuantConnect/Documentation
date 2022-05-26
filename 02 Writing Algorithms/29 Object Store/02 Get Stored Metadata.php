<p>Follow these steps to get all of the keys, values, and file paths in the Object Store:</p>

<ol>
    <li class="csharp">Load the required libraries and files.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using System.Xml.Linq;
using QuantConnect;
using QuantConnect.Data;
using QuantConnect.Algorithm;
using QuantConnect.Research;</pre>
    </div>

    <li>Instantiate a <code>QuantBook</code> object.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Iterate the <code>ObjectStore</code> member:</li>
    <div class="section-example-container">
        <pre class="csharp">foreach (var kvp in qb.ObjectStore)
{
    var key = kvp.Key;
    var value = kvp.Value;
    var filePath = objectStore.GetFilePath(key);
    Console.WriteLine($"{key} : {filePath}");
}</pre>
        <pre class="python">for kvp in qb.ObjectStore:
    key = kvp.Key
    value = kvp.Value
    file_path = object_store.GetFilePath(key)
    print(f"{key} : {file_path}")</pre>
    </div>
</ol>