<p>As your data reader reads your custom data file, LEAN adds the data points in the <code>Slice</code> it passes to your algorithm's <code>OnData</code> method. To collect the custom data, use the <code>Symbol</code> or name of your custom data subscription. You can access the <code>Value</code> and custom properties of your custom data class from the <code>Slice</code>. To access the custom properties, <span class='csharp'>use the custom attribute</span><span class='python'>pass the property name to the <code>GetProperty</code> method</span>.</p>
<? if ($keyConceptsPage) { ?>
<div class='section-example-container'>
<pre class='csharp'>public class MyAlgorithm : QCAlgorithm
{
    public override void OnData(Slice slice)
    {
        if (slice.ContainsKey(_symbol))
        {
            var customData = slice[_symbol];
            var value = customData.Value;
            var property1 = customData.Property1;
        }
    }

    // You can also get the data directly with OnData(&lt;dataClass&gt;) method
    public void OnData(MyCustomDataType slice)
    {
        var value = slice.Value;
        var property1 = slice.Property1;
    }
}</pre>
<pre class='python'>class MyAlgorithm(QCAlgorithm):
    def on_data(self, slice: Slice) -&gt; None:
        if slice.contains_key(self.symbol):
            custom_data = slice[self.symbol]
            value = custom_data.value
            property1 = custom_data.property1</pre>
</div>
<? } else { ?>
<div class='section-example-container'>
<pre class='csharp'>public class MyAlgorithm : QCAlgorithm
{
    public override void OnData(Slice slice)
    {
        if (slice.ContainsKey(_symbol))
        {
            var customData = slice[_symbol];
            var close = customData.Close;
        }
    }

    // You can also get the data directly with OnData(&lt;dataClass&gt;) method
    public void OnData(MyCustomDataType customData)
    {
        var close = customData.Close;
    }
}</pre>
<pre class='python'>class MyAlgorithm(QCAlgorithm):
    def on_data(self, slice: Slice) -&gt; None:
        if slice.contains_key(self.symbol):
            custom_data = slice[self.symbol]
            close = custom_data.close</pre>
</div>
<p class='python'>If you add custom properties to your data object in the <code>Reader</code> method, LEAN adds them as members to the data object in the <code>Slice</code>. To ensure the property names you add in the <code>Reader</code> method follow the convention of member names, LEAN applies the following changes to the property names you provide in the <code>Reader</code> method:</p>
<ol class='python'>
    <li><code>-</code> and <code>.</code> characters are replaced with whitespace.</li>
    <li>The first letter is capitalized.</li>
    <li>Whitespace characters are removed.</li>
</ol>
<p class='python'>For example, if you set a property name in the <code>Reader</code> method to <code>['some-property.name']</code>, you can access it in the <code>OnData</code> method through the <code>Somepropertyname</code> member of your data object.</p>
<? } ?>
