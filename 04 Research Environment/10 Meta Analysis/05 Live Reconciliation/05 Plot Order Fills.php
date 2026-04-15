<p>Follow these steps to overlay live and OOS backtest order fills on a single marker-only chart per symbol. The chart deliberately omits candlesticks and any price history so the comparison between live and backtest executions is not drowned out by other series.</p>

<ol>
    <li>Read the live and backtest orders. Both endpoints can take a few seconds to load the first time, so retry until the response is non-empty.</li>
    <div class="section-example-container">
        <pre class="csharp">List&lt;ApiOrderResponse&gt; ReadOrders(Func&lt;List&lt;ApiOrderResponse&gt;&gt; fetch)
{
    for (var attempt = 0; attempt &lt; 10; attempt++)
    {
        var result = fetch();
        if (result.Any()) return result;
        Console.WriteLine($"Orders loading... (attempt {attempt + 1}/10)");
        Thread.Sleep(10000);
    }
    throw new Exception("Failed to read orders after 10 attempts");
}

var liveOrders = ReadOrders(() =&gt; api.ReadLiveOrders(projectId, 0, 100));
var backtestOrders = ReadOrders(() =&gt; api.ReadBacktestOrders(projectId, backtestId, 0, 100));</pre>
        <pre class="python">from time import sleep

def read_orders(fetch):
    for attempt in range(10):
        result = fetch()
        if result:
            return result
        print(f"Orders loading... (attempt {attempt + 1}/10)")
        sleep(10)
    raise RuntimeError("Failed to read orders after 10 attempts")

live_orders = read_orders(lambda: api.read_live_orders(project_id, 0, 100))
backtest_orders = read_orders(lambda: api.read_backtest_orders(project_id, backtest_id, 0, 100))</pre>
    </div>
    <p>By default, you receive the orders with an Id between 0 and 100. To read more, call the method repeatedly in windows of up to 100 Ids. For more on the order objects returned, see <a href='/docs/v2/research-environment/meta-analysis/live-analysis#03-Plot-Order-Fills'>Plot Order Fills</a> in the Live Analysis documentation.</p>

    <li>Organize the trade times and prices for each security into a dictionary for both the live and backtest fills.</li>
    <div class="section-example-container">
        <pre class="csharp">var liveBySymbol = liveOrders.Select(x =&gt; x.Order).GroupBy(o =&gt; o.Symbol);
var backtestBySymbol = backtestOrders.Select(x =&gt; x.Order)
    .GroupBy(o =&gt; o.Symbol)
    .ToDictionary(g =&gt; g.Key, g =&gt; g.ToList());</pre>
        <pre class="python">import pandas as pd

def to_naive(t):
    # Strip tzinfo so plotly can serialize the fill times.
    ts = pd.Timestamp(t)
    return ts.tz_convert('UTC').tz_localize(None) if ts.tzinfo else ts

class OrderData:
    def __init__(self):
        self.buy_fill_times = []
        self.buy_fill_prices = []
        self.sell_fill_times = []
        self.sell_fill_prices = []

def group_by_symbol(orders):
    data_by_symbol = {}
    for order in [x.order for x in orders]:
        if order.symbol not in data_by_symbol:
            data_by_symbol[order.symbol] = OrderData()
        data = data_by_symbol[order.symbol]
        is_buy = order.quantity &gt; 0
        (data.buy_fill_times if is_buy else data.sell_fill_times).append(to_naive(order.last_fill_time))
        (data.buy_fill_prices if is_buy else data.sell_fill_prices).append(order.price)
    return data_by_symbol

live_by_symbol = group_by_symbol(live_orders)
backtest_by_symbol = group_by_symbol(backtest_orders)</pre>
    </div>

    <li>Plot one figure per symbol with four marker traces: live buys, live sells, backtest buys, backtest sells. Distinct markers keep live versus backtest executions visually separable.</li>
    <div class="section-example-container">
        <pre class="csharp">foreach (var liveGroup in liveBySymbol)
{
    var symbol = liveGroup.Key;
    var live = liveGroup.ToList();
    var bt = backtestBySymbol.TryGetValue(symbol, out var btList) ? btList : new List&lt;Order&gt;();

    var traces = new[]
    {
        Chart2D.Chart.Point&lt;DateTime, decimal, string&gt;(
            live.Where(o =&gt; o.Quantity &gt; 0).Select(o =&gt; o.LastFillTime ?? o.Time),
            live.Where(o =&gt; o.Quantity &gt; 0).Select(o =&gt; o.Price),
            Name: "Live Buys"),
        Chart2D.Chart.Point&lt;DateTime, decimal, string&gt;(
            live.Where(o =&gt; o.Quantity &lt; 0).Select(o =&gt; o.LastFillTime ?? o.Time),
            live.Where(o =&gt; o.Quantity &lt; 0).Select(o =&gt; o.Price),
            Name: "Live Sells"),
        Chart2D.Chart.Point&lt;DateTime, decimal, string&gt;(
            bt.Where(o =&gt; o.Quantity &gt; 0).Select(o =&gt; o.LastFillTime ?? o.Time),
            bt.Where(o =&gt; o.Quantity &gt; 0).Select(o =&gt; o.Price),
            Name: "OOS Backtest Buys"),
        Chart2D.Chart.Point&lt;DateTime, decimal, string&gt;(
            bt.Where(o =&gt; o.Quantity &lt; 0).Select(o =&gt; o.LastFillTime ?? o.Time),
            bt.Where(o =&gt; o.Quantity &lt; 0).Select(o =&gt; o.Price),
            Name: "OOS Backtest Sells")
    };
    var fillsChart = PlotlyChart.Combine(traces).WithTitle($"{symbol} Live vs OOS Backtest Fills");
    display(fillsChart);
}</pre>
        <pre class="python">import plotly.graph_objects as go

symbols = set(live_by_symbol.keys()) | set(backtest_by_symbol.keys())

for symbol in symbols:
    live = live_by_symbol.get(symbol, OrderData())
    bt = backtest_by_symbol.get(symbol, OrderData())

    fig = go.Figure(layout=go.Layout(
        title=go.layout.Title(text=f'{symbol.value} Live vs OOS Backtest Fills'),
        xaxis_title='Fill Time',
        yaxis_title='Fill Price',
        height=600
    ))

    fig.add_trace(go.Scatter(
        x=live.buy_fill_times, y=live.buy_fill_prices, mode='markers', name='Live Buys',
        marker=go.scatter.Marker(color='aqua', symbol='triangle-up', size=12)
    ))
    fig.add_trace(go.Scatter(
        x=live.sell_fill_times, y=live.sell_fill_prices, mode='markers', name='Live Sells',
        marker=go.scatter.Marker(color='indigo', symbol='triangle-down', size=12)
    ))
    fig.add_trace(go.Scatter(
        x=bt.buy_fill_times, y=bt.buy_fill_prices, mode='markers', name='OOS Backtest Buys',
        marker=go.scatter.Marker(color='aqua', symbol='triangle-up-open', size=12, line=dict(width=2))
    ))
    fig.add_trace(go.Scatter(
        x=bt.sell_fill_times, y=bt.sell_fill_prices, mode='markers', name='OOS Backtest Sells',
        marker=go.scatter.Marker(color='indigo', symbol='triangle-down-open', size=12, line=dict(width=2))
    ))

    fig.show()</pre>
    </div>
</ol>

<p>Note: the preceding plots only show the last fill of each trade. If your trade has partial fills, the plots only display the last fill.</p>
