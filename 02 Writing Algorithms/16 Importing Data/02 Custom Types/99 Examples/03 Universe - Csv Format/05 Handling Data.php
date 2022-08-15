<p>As your data reader reads your custom data file, LEAN adds the data points into a <code class="python">List[StockDataSource])</code><code class="csharp">IEnumerable&lt;StockDataSource&gt;</code> object it passes to your algorithm's filter function. Your filter function needs to return a list of <code>Symbol</code> or <code class="python">str</code><code class="csharp">string</code> object. LEAN automatically subscribes to these new symbols and adds them to your algorithm.<p>

<div class="section-example-container">
    <pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    private IEnumerable&lt;string&gt; FilterFunction(IEnumerable&lt;StockDataSource&gt; stockDataSource)
    {
        return stockDataSource.SelectMany(x => x.Symbols);
    }
}</pre>
    <pre class="python">class MyAlgorithm(QCAlgorithm):
    def FilterFunction(self, data: List[StockDataSource]) -&gt; List[str]:
        list = []
        for item in data:
            for symbol in item["Symbols"]:
                list.append(symbol)
        return list
    </pre>
</div>