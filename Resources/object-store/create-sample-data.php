<?php
$getCreateSampleDataText = function($cSharpMethodName="Log")
{
    echo "
<p>You need some data to store data in the Object Store.</p>

<p>Follow these steps to create some sample data:</p>

<ol>
    <li class='csharp'>Create a dictionary.</li>
    <div class='csharp section-example-container'>
        <pre class='csharp'>var dictSample = new Dictionary&lt;string, int&gt; { {\"One\", 1}, {\"Two\", 2}, {\"Three\", 3} };</pre>
    </div>
    <li>Create a <code>string</code>.</li>
    <div class='section-example-container'>
        <pre class='csharp'>var stringSample = \"My string\";</pre>
        <pre class='python'>string_sample = \"My string\"</pre>
    </div>
    <li>Create a <code>Bytes</code> object.</li>
    <div class='section-example-container'>
        <pre class='csharp'>var bytesSample = Encoding.UTF8.GetBytes(\"My String\");</pre>
        <pre class='python'>bytes_sample = str.encode(\"My String\")</pre>
    </div>
    <li class='csharp'>Convert the dictionary to an <code>XML</code>-formatted object.</li>
    <div class='csharp section-example-container'>
        <pre class='csharp'>var xmlSample = new XElement(\"sample\",
    dictSample.Select(kvp => new XElement(kvp.Key, kvp.Value)));
{$cSharpMethodName}(xmlSample.ToString());</pre>
    </div>
    <img class='csharp' src='https://cdn.quantconnect.com/i/tu/store-data-xml-cs.png'>
</ol>
";
}
?>
