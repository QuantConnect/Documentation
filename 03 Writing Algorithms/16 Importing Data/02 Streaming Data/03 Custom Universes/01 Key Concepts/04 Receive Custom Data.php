<p>The universe selector function receives a list of your custom objects and must return a list of <code>Symbol</code> objects. In the selector function definition, you can use any of the properties of your custom data type. The <code>Symbol</code> objects that you return from the selector function set the constituents of the universe.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCustomUniverseAlgorithm : QCAlgorithm
{
	private IEnumerable&lt;Symbol&gt; SelectorFunction(IEnumerable&lt;MyCustomUniverseDataClass&gt; data)
	{
        return (from singleStockData in data
               where singleStockData.CustomAttribute1 &gt; 0
               orderby singleStockData.CustomAttribute2 descending
               select singleStockData.Symbol).Take(5);
    }
}
</pre>
<pre class="python">class MyCustomUniverseAlgorithm(QCAlgorithm):
	def selector_function(self, data: List[MyCustomUniverseDataClass]) -&gt; List[Symbol]:
    	sorted_data = sorted([ x for x in data if x["CustomAttribute1"] &gt; 0 ],
                         	key=lambda x: x["CustomAttribute2"],
                         	reverse=True)
    	return [x.symbol for x in sorted_data[:5]]
</pre>
</div>
