<p>As your data reader reads your custom data file, LEAN adds the data points in the <code>Slice</code> it passes to your algorithm's <code>OnData</code> method. To collect the custom data, use the <code>Symbol</code> or name of your custom data subscription. You can access the <code>Value</code> and custom properties of your custom data class from the <code>Slice</code>. To access the custom properties, <span class="csharp">use the custom attribute</span><span class="python">pass the property name to the <code>GetProperty</code> method</span>.</p>

<div class="section-example-container">
<pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    public override void OnData(Slice slice)
    {
        if (slice.ContainsKey(_symbol))
        {
            var customData = slice[_symbol];
            var close = customData.Close;
        }
    }

    // You can also get the data directly with OnData(dataClass) method
    public void OnData(MyCustomDataType customData)
    {
        var close = customData.Close;
    }
}</pre>
<pre class="python">class MyAlgorithm(QCAlgorithm):
    def OnData(self, slice: Slice) -&gt; None:
        if slice.ContainsKey(self.symbol):
            custom_data = slice[self.symbol]
            close = custom_data.Close</pre>
</div>