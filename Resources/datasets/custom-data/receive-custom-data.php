<?php

$getReceiveCustomDataText = function($keyConceptsPage)
{
    echo "
<p>As your data reader reads your custom data file, LEAN adds the data points in the <code>Slice</code> it passes to your algorithm's <code>OnData</code> method. To collect the custom data, use the <code>Symbol</code> or name of your custom data subscription. You can access the <code>Value</code> and custom properties of your custom data class from the <code>Slice</code>. To access the custom properties, <span class='csharp'>use the custom attribute</span><span class='python'>pass the property name to the <code>GetProperty</code> method</span>.</p>
    ";

    if ($keyConceptsPage)
    {
        echo "
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
    def OnData(self, slice: Slice) -&gt; None:
        if slice.ContainsKey(self.symbol):
            custom_data = slice[self.symbol]
            value = custom_data.Value
            property1 = custom_data.Property1</pre>
</div>
        ";
    }
    else 
    {
        echo "
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
    def OnData(self, slice: Slice) -&gt; None:
        if slice.ContainsKey(self.symbol):
            custom_data = slice[self.symbol]
            close = custom_data.Close</pre>
</div>
        ";
    }

}

?>



