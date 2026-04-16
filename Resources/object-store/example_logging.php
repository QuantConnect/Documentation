<p>You can use the Object Store to log order data from your backtests and live algorithms, then analyze it in the Research Environment. The following example demonstrates how to log orders from an <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/exponential-moving-average'>Exponential Moving Average</a> (EMA) cross strategy to a text file in the Object Store.</p>

<ol>
    <li>Create an algorithm, add a daily equity subscription, and create two EMA indicators.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreLoggingAlgorithm : QCAlgorithm
{
    private ExponentialMovingAverage _emaShort;
    private ExponentialMovingAverage _emaLong;
    private Symbol _symbol;
    private string _content;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _emaShort = EMA(_symbol, 10);
        _emaLong = EMA(_symbol, 30);
        // Add a header row.
        _content = "Time,Symbol,Price,Quantity,Tag\n";
    }
}</pre>
    <pre class='python'>class ObjectStoreLoggingAlgorithm(QCAlgorithm):
    def initialize(self):
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ema_short = self.ema(self._symbol, 10)
        self._ema_long = self.ema(self._symbol, 30)
        # Add a header row.
        self._content = 'Time,Symbol,Price,Quantity,Tag\n'</pre>
    </div>

    <p>The algorithm saves <code class='csharp'>_content</code><code class='python'>self._content</code> to the Object Store at the end of the backtest.</p>

    <li>In the <code>OnData</code> method, place market orders when the EMAs cross.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice data)
{
    if (!_emaShort.IsReady || !_emaLong.IsReady) return;

    if (_emaShort &gt; _emaLong &amp;&amp; !Portfolio[_symbol].IsLong)
    {
        MarketOrder(_symbol, 100, tag: $"BUY: ema-short: {_emaShort:F4} &gt; ema-long: {_emaLong:F4}");
    }
    else if (_emaShort &lt; _emaLong &amp;&amp; !Portfolio[_symbol].IsShort)
    {
        MarketOrder(_symbol, -100, tag: $"SELL: ema-short: {_emaShort:F4} &lt; ema-long: {_emaLong:F4}");
    }
}</pre>
    <pre class='python'>def on_data(self, data: Slice):
    if not self._ema_short.is_ready or not self._ema_long.is_ready:
        return

    ema_short = self._ema_short.current.value
    ema_long = self._ema_long.current.value
    if ema_short &gt; ema_long and not self.portfolio[self._symbol].is_long:
        self.market_order(self._symbol, 100, tag=f'BUY: ema-short: {ema_short:.4f} &gt; ema-long: {ema_long:.4f}')
    elif ema_short &lt; ema_long and not self.portfolio[self._symbol].is_short:
        self.market_order(self._symbol, -100, tag=f'SELL: ema-short: {ema_short:.4f} &lt; ema-long: {ema_long:.4f}')</pre>
    </div>

    <li>In the <code>OnOrderEvent</code> method, log each fill to the content string.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (orderEvent.Status != OrderStatus.Filled) return;

    _content += $"{orderEvent.UtcTime:yyyy-MM-dd},{orderEvent.Symbol},{orderEvent.FillPrice}," +
        $"{orderEvent.FillQuantity},{orderEvent.Ticket.Tag}\n";
}</pre>
    <pre class='python'>def on_order_event(self, order_event: OrderEvent):
    if order_event.status != OrderStatus.FILLED:
        return

    self._content += (
        f'{order_event.utc_time.strftime("%Y-%m-%d")},'
        f'{order_event.symbol},'
        f'{order_event.fill_price},'
        f'{order_event.fill_quantity},'
        f'{order_event.ticket.tag}\n'
    )</pre>
    </div>

    <li>In the <a href='/docs/v2/writing-algorithms/key-concepts/event-handlers#15-End-Of-Algorithm-Events'>OnEndOfAlgorithm</a> method, save the content to the Object Store.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public override void OnEndOfAlgorithm()
{
    var key = $"{ProjectId}-{AlgorithmId}.txt";
    ObjectStore.Save(key, _content);
    Log($"Saved order log to Object Store: {key}");
}</pre>
    <pre class='python'>def on_end_of_algorithm(self):
    key = f'{self.project_id}-{self.algorithm_id}.txt'
    self.object_store.save(key, self._content)
    self.log(f'Saved order log to Object Store: {key}')</pre>
    </div>

    <li><a href='/docs/v2/research-environment/key-concepts/getting-started#03-Open-Notebooks'>Open the Research Environment</a> and create a <code>QuantBook</code>.</li>

    <div class='section-example-container'>
    <pre class='csharp'>// Execute the following command in first
#load "../Initialize.csx"</pre>
    </div>
    <div class='section-example-container'>
    <pre class='csharp'>// Load the necessary assembly files.
#load "../QuantConnect.csx"</pre>
    </div>
    <div class='section-example-container'>
    <pre class='csharp'>using QuantConnect;
using QuantConnect.Research;

var qb = new QuantBook();</pre>
    <pre class='python'>qb = QuantBook()</pre>
    </div>

    <li>Read the data from the Object Store.</li>

    <div class='section-example-container'>
    <pre class='csharp'>// Replace the key with the one from your backtest log.
var content = qb.ObjectStore.Read("&lt;project-id&gt;-&lt;algorithm-id&gt;.txt");</pre>
    <pre class='python'># Replace the key with the one from your backtest log.
content = qb.object_store.read("&lt;project-id&gt;-&lt;algorithm-id&gt;.txt")</pre>
    </div>

    <p>The key you provide must be the same key you used to save the object. Check the backtest log for the exact key.</p>

    <li class='python'>Split the content into lines.</li>
    <div class="python section-example-container">
    <pre class="python">lines = content.strip().split('\n')
# Display the header.
print(lines[0])</pre>
    </div>

    <li class='csharp'>Parse the content into a list of string arrays.</li>
    <div class="csharp section-example-container">
    <pre class="csharp">var lines = content.Split('\n')
    .Where(line =&gt; !string.IsNullOrWhiteSpace(line))
    .Select(line =&gt; line.Split(','))
    .ToList();

// Display the header row.
var header = lines.First();
Console.WriteLine(string.Join(" | ", header));</pre>
    </div>

    <li class='python'>Display the first few rows (head) and last few rows (tail).</li>
    <div class="python section-example-container">
    <pre class="python"># Show the first 5 data rows.
for line in lines[1:6]:
    print(line)

# Show the last 5 data rows.
for line in lines[-5:]:
    print(line)</pre>
    </div>

    <li class='csharp'>Display the first few rows (head) and last few rows (tail).</li>
    <div class="csharp section-example-container">
    <pre class="csharp">// Skip the header, then take the first 5 data rows.
var dataRows = lines.Skip(1).ToList();
Console.WriteLine("Head:");
foreach (var row in dataRows.Take(5))
{
    Console.WriteLine(string.Join(" | ", row));
}

// Show the last 5 data rows.
Console.WriteLine("\nTail:");
foreach (var row in dataRows.TakeLast(5))
{
    Console.WriteLine(string.Join(" | ", row));
}</pre>
    </div>

    <li class='python'>Search for all rows tagged as "BUY".</li>
    <div class="python section-example-container">
    <pre class="python"># Filter lines that contain "BUY".
buy_orders = [line for line in lines[1:] if 'BUY' in line]
for line in buy_orders:
    print(line)</pre>
    </div>

    <li class='csharp'>Search for all rows tagged as "BUY".</li>
    <div class="csharp section-example-container">
    <pre class="csharp">// Filter rows where Tag contains "BUY".
var buyOrders = dataRows
    .Where(row =&gt; row.Length &gt; 4 &amp;&amp; row[4].Contains("BUY"))
    .ToList();

Console.WriteLine("Buy Orders:");
foreach (var row in buyOrders)
{
    Console.WriteLine(string.Join(" | ", row));
}</pre>
    </div>
</ol>

<div class="section-example-container testable">
    <pre class="csharp">public class ObjectStoreLoggingAlgorithm : QCAlgorithm
{
    private ExponentialMovingAverage _emaShort;
    private ExponentialMovingAverage _emaLong;
    private Symbol _symbol;
    private string _content;

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        // Create short and long EMA indicators.
        _emaShort = EMA(_symbol, 10);
        _emaLong = EMA(_symbol, 30);
        // Add a header row.
        _content = "Time,Symbol,Price,Quantity,Tag\n";
    }

    public override void OnData(Slice data)
    {
        if (!_emaShort.IsReady || !_emaLong.IsReady) return;

        // Place a market order when the EMAs cross.
        if (_emaShort &gt; _emaLong &amp;&amp; !Portfolio[_symbol].IsLong)
        {
            MarketOrder(_symbol, 100, tag: $"BUY: ema-short: {_emaShort:F4} &gt; ema-long: {_emaLong:F4}");
        }
        else if (_emaShort &lt; _emaLong &amp;&amp; !Portfolio[_symbol].IsShort)
        {
            MarketOrder(_symbol, -100, tag: $"SELL: ema-short: {_emaShort:F4} &lt; ema-long: {_emaLong:F4}");
        }
    }

    public override void OnOrderEvent(OrderEvent orderEvent)
    {
        if (orderEvent.Status != OrderStatus.Filled) return;

        // Log each fill as a row.
        _content += $"{orderEvent.UtcTime:yyyy-MM-dd},{orderEvent.Symbol},{orderEvent.FillPrice}," +
            $"{orderEvent.FillQuantity},{orderEvent.Ticket.Tag}\n";
    }

    public override void OnEndOfAlgorithm()
    {
        // Save the order log to the Object Store.
        var key = $"{ProjectId}-{AlgorithmId}.txt";
        ObjectStore.Save(key, _content);
        Log($"Saved order log to Object Store: {key}");
    }
}</pre>
    <pre class="python">class ObjectStoreLoggingAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        # Create short and long EMA indicators.
        self._ema_short = self.ema(self._symbol, 10)
        self._ema_long = self.ema(self._symbol, 30)
        # Add a header row.
        self._content = 'Time,Symbol,Price,Quantity,Tag\n'

    def on_data(self, data: Slice) -&gt; None:
        if not self._ema_short.is_ready or not self._ema_long.is_ready:
            return

        # Place a market order when the EMAs cross.
        ema_short = self._ema_short.current.value
        ema_long = self._ema_long.current.value
        if ema_short &gt; ema_long and not self.portfolio[self._symbol].is_long:
            self.market_order(self._symbol, 100, tag=f'BUY: ema-short: {ema_short:.4f} &gt; ema-long: {ema_long:.4f}')
        elif ema_short &lt; ema_long and not self.portfolio[self._symbol].is_short:
            self.market_order(self._symbol, -100, tag=f'SELL: ema-short: {ema_short:.4f} &lt; ema-long: {ema_long:.4f}')

    def on_order_event(self, order_event: OrderEvent) -&gt; None:
        if order_event.status != OrderStatus.FILLED:
            return

        # Log each fill as a row.
        self._content += (
            f'{order_event.utc_time.strftime("%Y-%m-%d")},'
            f'{order_event.symbol},'
            f'{order_event.fill_price},'
            f'{order_event.fill_quantity},'
            f'{order_event.ticket.tag}\n'
        )

    def on_end_of_algorithm(self) -&gt; None:
        # Save the order log to the Object Store.
        key = f'{self.project_id}-{self.algorithm_id}.txt'
        self.object_store.save(key, self._content)
        self.log(f'Saved order log to Object Store: {key}')</pre>
</div>
