<?
// Pages that already document the order factory or buying power set these flags to skip the self-link.
$factoryText = isset($linkOrderFactory) && !$linkOrderFactory ? "order factory" : "<a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-factory'>order factory</a>";
$buyingPowerText = isset($linkBuyingPower) && !$linkBuyingPower ? "buying power" : "<a href='/docs/v2/writing-algorithms/reality-modeling/buying-power'>buying power</a>";
?>
<p>The following sections explain how to check if you have enough <?=$buyingPowerText?> to cover the initial <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#03-What-is-Margin3F'>margin requirements</a> of an order before you place it.</p>

<h4>Check Requirements of Regular Orders</h4>
<p>To check if you have enough buying power for a regular order, create an order request with the <?=$factoryText?> and convert it into an <code>Order</code> object. Then compare the absolute initial margin that the order requires with the buying power of your portfolio in the direction of the order. The initial margin is negative for sell orders and includes the modeled order fees. The buying power in the direction of the order includes the margin that the order frees when it reduces or reverses a position.</p>

<div class="section-example-container">
<pre class="csharp">var security = Securities[_symbol];
var request = OrderFactory.MarketOrder(_symbol, 100);
var order = QuantConnect.Orders.Order.CreateOrder(request);
var initialMargin = security.BuyingPowerModel.GetInitialMarginRequiredForOrder(
    new InitialMarginRequiredForOrderParameters(Portfolio.CashBook, security, order)).Value;
if (Math.Abs(initialMargin) &lt;= Portfolio.GetBuyingPower(_symbol, order.Direction))
{
    Order(request);
}</pre>
<pre class="python">security = self.securities[self._symbol]
request = self.order_factory.market_order(self._symbol, 100)
order = Order.create_order(request)
initial_margin = security.buying_power_model.get_initial_margin_required_for_order(
    InitialMarginRequiredForOrderParameters(self.portfolio.cash_book, security, order)).value
if abs(initial_margin) &lt;= self.portfolio.get_buying_power(self._symbol, order.direction):
    self.order(request)</pre>
</div>

<p>The <code class="csharp">HasSufficientBuyingPowerForOrder</code><code class="python">has_sufficient_buying_power_for_order</code> method of the security buying power model requires an order ticket, so it only works for orders that you already submitted. When you submit the order, LEAN runs its own buying power check.</p>

<h4>Check Requirements of Option Strategy Orders</h4>
<p>To check if you have enough buying power for an <a href='/docs/v2/writing-algorithms/trading-and-orders/option-strategies'>Option strategy</a> order, follow these steps:</p>

<ol>
    <li>Create an <code>OptionStrategy</code> object with the strategy you want to trade.</li>
    <p>For example, create a <a href='/docs/v2/writing-algorithms/trading-and-orders/option-strategies/bull-put-spread'>Bull Put Spread</a> strategy.</p>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    // Subscribe to option data and cache the canonical symbol to obtain the option data
    _symbol = AddOption("SPY").Symbol;
}

public override void OnData(Slice slice)
{
    // Trade on updated option chain data
    if (!slice.OptionChains.TryGetValue(_symbol, out var chain))
    {
        return;
    }

    var itmStrike = chain.Max(x =&gt; x.Strike);
    var otmStrike = chain.Min(x =&gt; x.Strike);
    var expiry = chain.Min(x =&gt; x.Expiry);

    var optionStrategy = OptionStrategies.BullPutSpread(_symbol, itmStrike, otmStrike, expiry);</pre>
        <pre class="python">def initialize(self) -&gt; None:
    # Subscribe to option data and cache the canonical symbol to obtain the option data
    self._symbol = self.add_option("SPY").symbol

def on_data(self, slice: Slice) -&gt; None:
    # Trade on updated option chain data
    chain = slice.option_chains.get(self._symbol)
    if not chain:
        return

    itm_strike = max(x.strike for x in chain)
    otm_strike = min(x.strike for x in chain)
    expiry = min(x.expiry for x in chain)

    option_strategy = OptionStrategies.bull_put_spread(self._symbol, itm_strike, otm_strike, expiry)</pre>
    </div>

    <li>Create the order requests of the strategy legs with the <?=$factoryText?> and convert each request into an <code>Order</code> object.</li>
    <div class="section-example-container">
        <pre class="csharp">    var requests = OrderFactory.OptionStrategyOrder(optionStrategy, 2);
    var orders = requests.Select(request =&gt; QuantConnect.Orders.Order.CreateOrder(request)).ToList();</pre>
        <pre class="python">    requests = self.order_factory.option_strategy_order(option_strategy, 2)
    orders = [Order.create_order(request) for request in requests]</pre>
    </div>

    <li>Call the <code class="csharp">HasSufficientBuyingPowerForOrder</code><code class="python">has_sufficient_buying_power_for_order</code> method of the portfolio with the list of orders.</li>
    <div class="section-example-container">
        <pre class="csharp">    var result = Portfolio.HasSufficientBuyingPowerForOrder(orders);</pre>
        <pre class="python">    result = self.portfolio.has_sufficient_buying_power_for_order(orders)</pre>
    </div>

    <p>The <code class="csharp">HasSufficientBuyingPowerForOrder</code><code class="python">has_sufficient_buying_power_for_order</code> method returns a <code>HasSufficientBuyingPowerForOrderResult</code> object, which has the following properties:</p>
    <div data-tree='QuantConnect.Securities.HasSufficientBuyingPowerForOrderResult'></div>

    <li>If the result is sufficient, submit the order requests.</li>
    <div class="section-example-container">
        <pre class="csharp">    if (result.IsSufficient)
    {
        Order(requests);
    }
    else
    {
        Debug($"You don't have sufficient margin for this order: {result.Reason}");
    }
}</pre>
        <pre class="python">    if result.is_sufficient:
        self.order(requests)
    else:
        self.debug(f"You don't have sufficient margin for this order: {result.reason}")</pre>
    </div>
</ol>

<p>The method combines the orders with your current holdings into <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#05-What-Are-Position-Groups3F'>position groups</a>, so it accounts for strategy orders that reduce or close a position. If the strategy has a single leg, such as a naked put, follow the steps in the preceding section.</p>
