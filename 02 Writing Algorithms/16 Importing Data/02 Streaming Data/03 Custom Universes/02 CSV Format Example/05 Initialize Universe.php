<p>To perform a universe selection with custom data, in the <code>Initialize</code> method, call the <code>AddUniverse</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    AddUniverse&lt;StockDataSource&gt;("myStockDataSource", Resolution.Daily, FilterFunction);
}</pre>
    <pre class="python">class MyAlgorithm(QCAlgorithm): 
    def Initialize(self) -&gt; None:
        self.AddUniverse(StockDataSource, "my-stock-data-source", Resolution.Daily, self.FilterFunction)
    </pre>
</div>