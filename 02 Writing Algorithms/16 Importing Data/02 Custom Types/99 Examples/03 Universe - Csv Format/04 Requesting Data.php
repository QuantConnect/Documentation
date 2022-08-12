<p>To perform a universe selection for the custom type, in the <code>Initialize</code> method, call the <code>AddUniverse</code> method. The <code>AddUniverse</code> method returns a list of <code>Symbol</code> or <code class="python">str</code><code class="csharp">string</code> object, which will be added to your <span class="new-term">user-defined</span> universe for a day (default) or a custom period.</p>

<div class="section-example-container">
    <pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    AddUniverse&lt;StockDataSource&gt;("myStockDataSource", Resolution.Daily, stockDataSource =>
    {
        return stockDataSource.SelectMany(x => x.Symbols);
    });
}</pre>
    <pre class="python">class MyAlgorithm(QCAlgorithm): 
    def Initialize(self) -&gt; None:
        self.symbol = self.AddUniverse(StockDataSource, "my-stock-data-source", Resolution.Daily, self.stockDataSource)

    def stockDataSource(self, data: List[StockDataSource]) -&gt; List[str]:
        list = []
        for item in data:
            for symbol in item["Symbols"]:
                list.append(symbol)
        return list
    </pre>
</div>