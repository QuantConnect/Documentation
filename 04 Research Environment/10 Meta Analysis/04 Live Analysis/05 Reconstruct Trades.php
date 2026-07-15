<p>When you stop a live algorithm, it loses its in-memory state. After you redeploy the project, the <a href='/docs/v2/writing-algorithms/trading-and-orders/trade-statistics'>trade builder</a> starts empty, so its <code class="csharp">ClosedTrades</code><code class="python">closed_trades</code> property only contains the trades of the current deployment. Follow these steps to reconstruct the complete trade list across all the live deployments of a project in the Research Environment:</p>

<div class="section-example-container csharp">
    <pre class="csharp">#load "../Initialize.csx"</pre>
</div>
<div class="section-example-container csharp">
    <pre class="csharp">#load "../QuantConnect.csx"</pre>
</div>

<ol>
    <li>Define the project Id and get the deployments of the project.</li>
    <div class="section-example-container">
        <pre class="csharp">using QuantConnect;
using QuantConnect.Api;
using QuantConnect.Orders;
using QuantConnect.Statistics;

var projectId = 12345678;

// Request each status separately. Requests without a status filter can time out on accounts with many deployments.
var statuses = new[]
{
    AlgorithmStatus.Running, AlgorithmStatus.Stopped, AlgorithmStatus.Liquidated, AlgorithmStatus.RuntimeError
};
var deployments = statuses
    .SelectMany(status =&gt; api.ListLiveAlgorithms(status).Algorithms)
    .Where(x =&gt; x.ProjectId == projectId)
    .OrderBy(x =&gt; x.Launched)
    .ToList();
foreach (var deployment in deployments)
{
    Console.WriteLine($"{deployment.DeployId}: {deployment.Launched} - {deployment.Stopped}");
}</pre>
        <pre class="python">project_id = 12345678

# Request each status separately. Requests without a status filter can time out on accounts with many deployments.
statuses = [
    AlgorithmStatus.RUNNING, AlgorithmStatus.STOPPED, AlgorithmStatus.LIQUIDATED, AlgorithmStatus.RUNTIME_ERROR
]
deployments = sorted(
    [x for status in statuses for x in api.list_live_algorithms(status).algorithms if x.project_id == project_id],
    key=lambda x: x.launched
)
for deployment in deployments:
    print(f'{deployment.deploy_id}: {deployment.launched} - {deployment.stopped}')</pre>
    </div>

    <p>The following table provides links to documentation that explains how to get the project Id, depending on the platform you use:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th style='width: 50%'>Platform</th>
            <th>Project Id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cloud Platform</td>
            <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
        </tr>
        <tr>
            <td>Local Platform</td>
            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
        </tr>
        <tr>
            <td>CLI</td>
            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
        </tr>
    </tbody>
</table>

    <p>The <code class="csharp">ListLiveAlgorithms</code><code class="python">list_live_algorithms</code> method returns a list of deployment summaries with the <code class="csharp">DeployId</code><code class="python">deploy_id</code>, <code class="csharp">Launched</code><code class="python">launched</code>, and <code class="csharp">Stopped</code><code class="python">stopped</code> times of each deployment. The reconstruction in the following steps doesn't need them, but each fill event carries its deployment Id, so you can use these summaries to relate the reconstructed trades to a specific deployment.</p>

    <li>Get all the orders of the project.</li>
    <div class="section-example-container">
        <pre class="csharp">List&lt;ApiOrderResponse&gt; ReadAllOrders(Func&lt;int, int, List&lt;ApiOrderResponse&gt;&gt; fetchWindow)
{
    var all = new List&lt;ApiOrderResponse&gt;();
    // Retry the first window while the response is empty (may be loading).
    List&lt;ApiOrderResponse&gt; first = null;
    for (var attempt = 0; attempt &lt; 10; attempt++)
    {
        first = fetchWindow(0, 100);
        if (first.Any()) break;
        Console.WriteLine($"Orders loading... (attempt {attempt + 1}/10)");
        Thread.Sleep(10000);
    }
    if (first == null || !first.Any()) return all;
    all.AddRange(first);
    // Paginate in 100-index windows until the endpoint returns an empty window.
    var start = 100;
    while (true)
    {
        var window = fetchWindow(start, start + 100);
        if (!window.Any()) break;
        all.AddRange(window);
        start += 100;
    }
    return all;
}

var orders = ReadAllOrders((s, e) =&gt; api.ReadLiveOrders(projectId, s, e));
Console.WriteLine($"Orders: {orders.Count}");</pre>
        <pre class="python">from time import sleep

def read_all_orders(fetch_window):
    orders = []
    # Retry the first window while the response is empty (may be loading).
    first = []
    for attempt in range(10):
        first = fetch_window(0, 100)
        if first:
            break
        print(f"Orders loading... (attempt {attempt + 1}/10)")
        sleep(10)
    if not first:
        return orders
    orders.extend(first)
    # Paginate in 100-index windows until the endpoint returns an empty window.
    start = 100
    while True:
        window = fetch_window(start, start + 100)
        if not window:
            break
        orders.extend(window)
        start += 100
    return orders

orders = read_all_orders(lambda s, e: api.read_live_orders(project_id, s, e))
print(f'Orders: {len(orders)}')</pre>
    </div>
    <p>The <code class="csharp">ReadLiveOrders</code><code class="python">read_live_orders</code> method returns the orders of every deployment of the project, concatenated in the order the deployments launched, so a single project-level loop retrieves the complete order history. Each call returns at most 100 orders, so paginate in 100-index windows until the endpoint returns an empty window, like the <a href='/docs/v2/research-environment/meta-analysis/live-reconciliation#05-Plot-Order-Fills'>Live Reconciliation</a> tutorial does. Each element of the result is an <code>ApiOrderResponse</code> object that contains the order and its fill events.</p>

    <li>Replay the fill events through a new trade builder.</li>
    <div class="section-example-container">
        <pre class="csharp">// Use the same grouping and matching methods as the trade builder in your algorithm.
var tradeBuilder = new TradeBuilder(FillGroupingMethod.FillToFill, FillMatchingMethod.FIFO);

// The orders are already in deployment-launch order, so replay them in list order.
foreach (var response in orders)
{
    foreach (var serializedEvent in response.Events.Where(x =&gt; x.FillQuantity != 0))
    {
        var fill = OrderEvent.FromSerialized(serializedEvent);
        tradeBuilder.ProcessFill(fill, 1m, serializedEvent.OrderFeeAmount ?? 0m);
    }
}</pre>
        <pre class="python"># Use the same grouping and matching methods as the trade builder in your algorithm.
trade_builder = TradeBuilder(FillGroupingMethod.FILL_TO_FILL, FillMatchingMethod.FIFO)

# The orders are already in deployment-launch order, so replay them in list order.
for response in orders:
    for event in [x for x in response.events if x.fill_quantity != 0]:
        fill = OrderEvent.from_serialized(event)
        trade_builder.process_fill(fill, 1, event.order_fee_amount if event.order_fee_amount else 0)</pre>
    </div>
    <p>Each fill event carries the Id of the deployment that produced it in its <code class="csharp">AlgorithmId</code><code class="python">algorithm_id</code> property, so you can group the fills by deployment.</p>

    <li>Get the closed trades of the trade builder.</li>
    <div class="section-example-container">
        <pre class="csharp">foreach (var trade in tradeBuilder.ClosedTrades)
{
    Console.WriteLine($"{trade.Symbols[0].Value} {trade.Direction} quantity {trade.Quantity} " +
        $"entry {trade.EntryTime:u} @ {trade.EntryPrice} exit {trade.ExitTime:u} @ {trade.ExitPrice} " +
        $"profit {trade.ProfitLoss} fees {trade.TotalFees}");
}</pre>
        <pre class="python">trades = trade_builder.closed_trades
data = pd.DataFrame([{
    'symbol': trade.symbols[0].value,
    'direction': str(trade.direction),
    'quantity': trade.quantity,
    'entry_time': trade.entry_time,
    'entry_price': trade.entry_price,
    'exit_time': trade.exit_time,
    'exit_price': trade.exit_price,
    'profit_loss': trade.profit_loss,
    'total_fees': trade.total_fees
} for trade in trades])
data</pre>
    </div>
    <p>The <code class="csharp">ClosedTrades</code><code class="python">closed_trades</code> property returns a list of <code>Trade</code> objects, which have the following attributes:</p>
    <div data-tree='QuantConnect.Statistics.Trade'></div>
</ol>

<p>The preceding replay passes a conversion rate of 1 and the raw fee amount of each event, so it assumes the security and its fees are denominated in your account currency and the contract multiplier is 1. Adjust these arguments if these assumptions don't hold for the products you trade. Order Ids restart at 1 for each deployment and the trade builder assigns an order fee only to the first fill of each order Id, so the total fees exclude the fees of reused order Ids in later deployments. Positions that stay open across a redeployment form complete trades only because the replay is continuous across all the deployments.</p>
