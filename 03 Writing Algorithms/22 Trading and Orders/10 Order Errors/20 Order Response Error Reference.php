<p><a id='none'></a>The following sections explain why each <code>OrderResponseErrorCode</code> occurs and how to avoid it.</p>

<h4><a id='processing-error'></a>None</h4>

<p>The <code>OrderResponseErrorCode.None</code> (0) error means there is no order response error.</p>


<h4>Processing Error</h4>
<p>The <code>OrderResponseErrorCode.ProcessingError</code> (-1) error occurs in the following situations:</p>
<ul>
    <li>When you submit a new order, but LEAN throws an exception while checking if you have sufficient <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power'>buying power</a> for the order.</li>
    <li>When you try to update or cancel an order, but LEAN throws an exception.</li>
</ul>

<p><a id='order-already-exists'></a>To investigate this order response error further, see the <code>HandleSubmitOrderRequest</code>, <code>UpdateOrder</code>, and <code>CancelOrder</code> methods of the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Engine/TransactionHandlers/BrokerageTransactionHandler.cs'>BrokerageTransactionHandler</a> in the LEAN GitHub repository.</p>


<h4>Order Already Exists</h4>
<p>The <code>OrderResponseErrorCode.OrderAlreadyExists</code> (-2) error occurs when you submit a new order but you already have an open order or a completed order with the same order ID. This order response error usually comes from a concurrency issue.</p>

<p><a id='insufficient-buying-power'></a>To avoid this order response, don't place two asynchronous orders at the same time.</p>

<h4>Insufficient Buying Power</h4>
<p>The <code>OrderResponseErrorCode.InsufficientBuyingPower</code> (-3) error occurs when you place an order but the <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power'>buying power model</a> determines you can't afford it.</p>

<p>To avoid this order response error for non-Option trades, <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#13-Get-Initial-Margin-Requirements'>ensure you have enough margin remaining to cover the initial margin requirements</a> of the order before placing it.</p>

<p>This error also commonly occurs when you place a market on open order with daily data. If you place the order with <code>SetHoldings</code> or use <code>CalculateOrderQuantity</code> to determine the order quantity, LEAN calculates the order quantity based on the market close price. If the open price on the following day makes your order more expensive, then you may have insufficient buying power. To avoid the order response error in this case, either use intraday data and place trades when the market is open or <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing#05-Buying-Power-Buffer'>adjust your buying power buffer</a>.</p>

<a id='brokerage-model-refused-to-submit-order'></a><div class="section-example-container">
<pre class="csharp">Settings.FreePortfolioValuePercentage = 0.05m;</pre>
<pre class="python">self.Settings.FreePortfolioValuePercentage = 0.05</pre>
</div>

<h4>Brokerage Model Refused to Submit Order</h4>
<p>The <code>OrderResponseErrorCode.BrokerageModelRefusedToSubmitOrder</code> (-4) error occurs when you place an order but the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts'>brokerage model</a> determines it's invalid. The brokerage model usually checks your order meets the following requirements before sending it to the brokerage:</p>

<ul>
    <li>Supported security types</li>
    <li>Supported order types and their respective requirements</li>
    <li>Supported time in force options</li>
    <li>The order size is larger than the minimum order size</li>
</ul>

<p>Each brokerage model can have additional order requirements that the brokerage declares. To avoid this order response error, see the <span class='page-section-name'>Orders</span> section of the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage model documentation</a>.</p>

<p><a id='brokerage-failed-to-submit-order'></a>To investigate this order response error further, see the <code>CanSubmitOrder</code> method definition of your <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage model</a>. This order response error occurs when the <code>CanSubmitOrder</code> method returns <code class='csharp'>false</code><code class='python'>False</code>.</p>


<h4>Brokerage Failed to Submit Order</h4>
<p>The <code>OrderResponseErrorCode.BrokerageFailedToSubmitOrder</code> (-5) error occurs when you place an order but the brokerage implementation fails to submit the order to your brokerage.</p>

<p><a id='brokerage-failed-to-update-order'></a>To investigate this order response error further, see the <code>PlaceOrder</code> method definition of your <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage</a> or the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Brokerages/Backtesting/BacktestingBrokerage.cs'>BacktestingBrokerage</a> in the LEAN GitHub repository. This order response error occurs when the <code>PlaceOrder</code> method throws an error or returns <code>false</code>.</p>


<h4>Brokerage Failed to Update Order</h4>
<p>The <code>OrderResponseErrorCode.BrokerageFailedToUpdateOrder</code> (-6) error occurs when you try to update an order but the brokerage implementation fails to submit the order update request to your brokerage.</p>

<p>To avoid this order response error, see the <span class='page-section-name'>Orders</span> section of the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage model documentation</a>.</p>

<p><a id='brokerage-failed-to-cancel-order'></a>To investigate this order response error further, see the <code>UpdateOrder</code> method definition of your <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage</a> or the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Brokerages/Backtesting/BacktestingBrokerage.cs'>BacktestingBrokerage</a> in the LEAN GitHub repository. This order response error occurs when the <code>UpdateOrder</code> method throws an error or returns <code>false</code>.</p></p>

<h4>Brokerage Failed to Cancel Order</h4>
<p>The <code>OrderResponseErrorCode.BrokerageFailedToCancelOrder</code> (-8) error occurs when you try to cancel an order but the brokerage implementation fails to submit the cancel request to your brokerage.</p>    

<p><a id='invalid-order-status'></a>To investigate this order response error further, see the <code>CancelOrder</code> method definition of your <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage</a> or the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Brokerages/Backtesting/BacktestingBrokerage.cs'>BacktestingBrokerage</a> in the LEAN GitHub repository. This order response error occurs when <code>CancelOrder</code> method throws an error or returns <code>false</code>.</p>


<h4>Invalid Order Status</h4>
<p>The <code>OrderResponseErrorCode.InvalidOrderStatus</code> (-9) error occurs when you try to update or cancel an order but the order is already complete. An order is complete if it has <code>OrderStatus.Filled</code>, <code>OrderStatus.Canceled</code>, or <code>OrderStatus.Invalid</code>.</p>

<p>To avoid this order response error, check <code>Status</code> of an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets'>order ticket</a> or <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> before you update or cancel the order.</p>

<a id='unable-to-find-order'></a><div class="section-example-container">
<pre class="csharp">if (!_orderTicket.Status.IsClosed())
{
    _orderTicket.Cancel();
}</pre>
<pre class="python">if not OrderExtensions.IsClosed(order_ticket.Status):
    order_ticket.Cancel()</pre>
</div>


<h4>Unable to Find Order</h4>
<p>The <code>OrderResponseErrorCode.UnableToFindOrder</code> (-10) error occurs when you try to place, update, or cancel an order, but the <code>BrokerageTransactionHandler</code> doesn't have a record of the order ID.</p>

<p><a id='order-quantity-zero'></a>To investigate this order response error further, see <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Engine/TransactionHandlers/BrokerageTransactionHandler.cs'>BrokerageTransactionHandler.cs</a> in the LEAN GitHub repository. This order response error occurs when the <code>BrokerageTransactionHandler</code> can't find the order ID in it's <code>_completeOrders</code> or <code>_completeOrderTickets</code> dictionaries.</p>


<h4>Order Quantity Zero</h4>
<p>The <code>OrderResponseErrorCode.OrderQuantityZero</code> (-11) error occurs when you place an order that has zero quantity or when you update an order to have a zero quantity. This error commonly occurs if you use the <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing'>SetHoldings</a> method but the portfolio weight you provide to the method is too small to translate into a non-zero order quantity.</p>

<p>To avoid this order response error, check if the quantity of the order is non-zero before you place the order. If you use the <code>SetHoldings</code> method, replace it with a combination of the <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing#04-Calculate-Order-Quantities'>CalculateOrderQuantity</a> and <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a> methods.</p>

<a id='unsupported-request-type'></a><div class="section-example-container">
<pre class="csharp">var quantity = CalculateOrderQuantity(_symbol, 0.05);
if (quantity != 0)
{
    MarketOrder(_symbol, quantity);
}</pre>
<pre class="python">quantity = self.CalculateOrderQuantity(self.symbol, 0.05)
if quantity:
    self.MarketOrder(self.symbol, quantity)</pre>
</div>


<h4>Unsupported Request Type</h4>
<p>The <code>OrderResponseErrorCode.UnsupportedRequestType</code> (-12) error occurs in the following situations:</p>

<ul>
    <li>When you try to exercise an Option contract for which you hold a short position</li>
    <li>When you try to exercise more Option contracts than you hold</li>
</ul>

<p>To avoid this order response error, check the quantity of your holdings before you try to exercise an Option contract.</p>

<a id='missing-security'></a><div class="section-example-container">
<pre class="csharp">var holdingQuantity = Portfolio[_contractSymbol].Quantity;
if (holdingQuantity > 0)
{
    ExerciseOption(_contractSymbol, Math.Max(holdingQuantity, exerciseQuantity));
}</pre>
<pre class="python">holding_quantity = self.Portfolio[self.contract_symbol].Quantity
if holding_quantity > 0:
    self.ExerciseOption(self.contract_symbol, max(holding_quantity, exercise_quantity))</pre>
</div>


<h4>Missing Security</h4>
<p>The <code>OrderResponseErrorCode.MissingSecurity</code> (-14) error occurs when you place an order for a security but you don't have a subscription for the security in your algorithm.</p>

<p><a id='exchange-not-open'></a>To avoid this order response error, create a subscription for each security you want to trade. To create subscriptions, see the Requesting Data page of the <a href='/docs/v2/writing-algorithms/securities/asset-classes'>documentation for each asset class</a>.


<h4>Exchange Not Open</h4>
<p>The <code>OrderResponseErrorCode.ExchangeNotOpen</code> (-15) error occurs in the following situations:</p>

<ul>
    <li>When you try to exercise an Option while the exchange is not open</li>
    
    <p>To avoid the order response error in this case, check if the exchange is open before you exercise an Option contract.</p>
    
    <a id='security-price-zero'></a><div class="section-example-container">
    <pre class="csharp">if (IsMarketOpen(_contractSymbol))
{
    ExerciseOption(_contractSymbol, quantity);
}</pre>
    <pre class="python">if self.IsMarketOpen(self.contract_symbol):
    self.ExerciseOption(self.contract_symbol, quantity)</pre>
    </div>
    
    <li>When you try to place a market on open order for a Futures contract or a Future Option contract</li>
</ul>




<h4>Security Price Zero</h4>
<p>The <code>OrderResponseErrorCode.SecurityPriceZero</code> (-16) error occurs when you place an order or exercise an Option contract while the security price is $0. The security price can be $0 for the following reasons:</p>

<ul>
    <li>The data is missing</li>
    <p>Investigate if it's a data issue. If it is a data issue, <a href='/docs/v2/cloud-platform/datasets/data-issues#04-Report-New-Issues'>report it</a>.</p>

    <li>The algorithm hasn't received data for the security yet</li>
    <p><a id='forex-base-and-quote-currencies-required'></a><?php echo file_get_contents(DOCS_RESOURCES."/initialization/zero-price-error.html"); ?>
</ul>



<h4>Forex Base and Quote Currencies Required</h4>
<p><a id='forex-conversion-rate-zero'></a>The <code>OrderResponseErrorCode.ForexBaseAndQuoteCurrenciesRequired</code> (-17) error occurs when you place a trade for a Forex or Crypto pair but you don't have the base currency and <a href='/docs/v2/writing-algorithms/securities/key-concepts#03-Quote-Currency'>quote currency</a> in your <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cash book</a>. This error should never occur. If it does, create a bug report.</p>


<h4>Forex Conversion Rate Zero</h4>
<p><a id='security-has-no-data'></a>The <code>OrderResponseErrorCode.ForexConversionRateZero</code> (-18) error occurs when you place a trade for a Forex or Crypto pair and LEAN can't convert the value of the base currency to your account currency. This error usually indicates a lack of data. Investigate the data and if there is some missing, <a href='/docs/v2/cloud-platform/datasets/data-issues#04-Report-New-Issues'>report it</a>.</p>


<h4>Security Has No Data</h4>
<p><a id='exceeded-maximum-orders'></a>The <code>OrderResponseErrorCode.SecurityHasNoData</code> (-19) error occurs when you place an order for a security before your algorithm receives any data for it. <?php echo file_get_contents(DOCS_RESOURCES."/initialization/zero-price-error.html"); ?>




<h4>Exceeded Maximum Orders</h4>
<p>The <code>OrderResponseErrorCode.ExceededMaximumOrders</code> (-20) error occurs when exceed your order quota in a backtest. The number of orders you can place in a single backtest depends on the tier of your <a href='/docs/v2/cloud-platform/organizations/getting-started'>organization</a>. The following table shows the number of orders you can place on each tier:</p>

<a id='market-on-close-order-too-late'></a><?php echo file_get_contents(DOCS_RESOURCES."/quotas/orders.html"); ?>

<p>To avoid this order response error, reduce the number of orders in your backtest or <a href='/docs/v2/cloud-platform/organizations/billing#07-Change-Organization-Tiers'>upgrade your organization</a>.<br></p>


<h4>Market on Close Order Too Late</h4>
<p>The <code>OrderResponseErrorCode.MarketOnCloseOrderTooLate</code> (-21) error occurs when you try to place a market on close (MOC) order too early in the trading day.</p>

<p>To avoid this order response error, place the MOC order closer to the market close or adjust the submission time buffer. <a id='invalid-request'></a><?php echo file_get_contents(DOCS_RESOURCES."/order-types/moc-buffer.html"); ?>


<h4>Invalid Request</h4>
<p><a id='request-canceled'></a>The <code>OrderResponseErrorCode.InvalidRequest</code> (-22) error occurs when you try to cancel an order multiple times.</p>

<p>To avoid this order response error, only try to cancel an order one time.</p>


<h4>Request Canceled</h4>
<p><a id='algorithm-warming-up'></a>The <code>OrderResponseErrorCode.RequestCanceled</code> (-23) error occurs when you try to cancel an order multiple times.</p>

<p>To avoid this order response error, only try to cancel an order one time.</p>


<h4>Algorithm Warming Up</h4>
<p>The <code>OrderResponseErrorCode.AlgorithmWarmingUp</code> (-24) error occurs in the following situations:</p>

<ul>
    <li>When you try to place, update, or cancel an order during the <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm-up period</a></li>
    <li>When the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders#06-Option-Assignments'>Option assignment simulator</a> assigns you to an Option during the warm-up period</li>
</ul>

<p>To avoid this order response error, only manage orders after the warm-up period ends. To avoid trading during the warm-up period, add an <code>IsWarmingUp</code> guard to the top of the <code>OnData</code> method.</p>

<a id='brokerage-model-refused-to-update-order'></a><div class="section-example-container">
<pre class="csharp">if (IsWarmingUp) return;</pre>
<pre class="python">if self.IsWarmingUp: return</pre>
</div>


<h4>Brokerage Model Refused to Update Order</h4>
<p>The <code>OrderResponseErrorCode.BrokerageModelRefusedToUpdateOrder</code> (-25) error occurs in backtests when you try to update an order in a way that the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts'>brokerage model</a> doesn't support.</p>

<p><a id='quote-currency-required'></a>To avoid this issue, see the <span class='page-section-name'>Orders</span> section of the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage model documentation</a> to check its order requirements.</p>

<p>To investigate this order response error further, see the <code>CanUpdateOrder</code> method definition of your <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models'>brokerage model</a>.</p>



<h4>Quote Currency Required</h4>
<p><a id='conversion-rate-zero'></a>The <code>OrderResponseErrorCode.QuoteCurrencyRequired</code> (-26) error occurs when you place an order for a Forex or Crypto pair and don't have the <a href='/docs/v2/writing-algorithms/securities/key-concepts#03-Quote-Currency'>quote currency</a> of the pair in your <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cash book</a>. This error should never occur. If it does, create a bug report.</p>


<h4>Conversion Rate Zero</h4>
<p><a id='non-tradable-security'></a>The <code>OrderResponseErrorCode.ConversionRateZero</code> (-27) error occurs when you place an order for a Forex or Crypto pair and LEAN can't convert the value of the <a href='/docs/v2/writing-algorithms/securities/key-concepts#03-Quote-Currency'>quote currency</a> in the pair to your account currency. This order response error usually indicates a lack of data. Investigate the data and if there is data missing, <a href='/docs/v2/cloud-platform/datasets/data-issues#04-Report-New-Issues'>report it</a>.</p>


<h4>Non-Tradable Security</h4>
<p>The <code>OrderResponseErrorCode.NonTradableSecurity</code> (-28) error occurs when you place an order for a security that's not <a href='/docs/v2/writing-algorithms/securities/key-concepts#07-Tradable-Status'>tradable</a>. To avoid this order response error, check if a security is tradable before you trade it.</p>

<a id='non-exercisable-security'></a><div class="section-example-container">
    <pre class="csharp">if (Securities[_symbol].IsTradable)
{
    MarketOrder(_symbol, quantity);
}</pre>
    <pre class="python">if self.Securities[self.symbol].IsTradable:
    self.MarketOrder(self.symbol, quantity)</pre>
</div>


<h4>Non-Exercisable Security</h4>
<p><a id='order-quantity-less-than-lot-size'></a>The <code>OrderResponseErrorCode.NonExercisableSecurity</code> (-29) error occurs when you call the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/option-exercise-orders'>ExerciseOption</a> method with a <code>Symbol</code> that doesn't reference an Option contract.</p>

<h4>Order Quantity Less Than Lot Size</h4>
<p>The <code>OrderResponseErrorCode.OrderQuantityLessThanLotSize</code> (-30) error occurs when you place an order with a quantity that's less than the lot size of the security.</p>

<p>To avoid this order response error, check if the order quantity is greater than or equal to the security lot size before you place an order.</p>

<a id='exceeds-shortable-quantity'></a><div class="section-example-container">
    <pre class="csharp">var lotSize = Securities[_symbol].SymbolProperties.LotSize;
if (quantity >= lotSize)
{
    MarketOrder(_symbol, quantity);
}</pre>
    <pre class="python">lot_size = self.Securities[self.symbol].SymbolProperties.LotSize
if quantity >= lot_size:
    self.MarketOrder(self.symbol, quantity)</pre>
</div>


<h4>Exceeds Shortable Quantity</h4>
<p>The <code>OrderResponseErrorCode.ExceedsShortableQuantity</code> (-31) error occurs when you place an order to short a security but the <a href='/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts'>shortable provider</a> of the brokerage model states there isn't enough shares to borrow. For a full example of this error, clone and run <a href='https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_e1834ede9a0efa6d134f87bd9fd30a70.html'>this backtest</a>.</p>

<p>To avoid this order response error, check if there are enough shares available before you place an order to short a security.</p>

<a id='invalid-new-order-status'></a><div class="section-example-container">
    <pre class="csharp">var availableToBorrow = BrokerageModel.GetShortableProvider().ShortableQuantity(_symbol, Time);
if (availableToBorrow == null || quantityToBorrow <= availableToBorrow)
{
    MarketOrder(_symbol, -quantityToBorrow);
}</pre>
    <pre class="python">available_to_borrow = self.BrokerageModel.GetShortableProvider().ShortableQuantity(self.symbol, self.Time)
if available_to_borrow == None or quantity_to_borrow <= available_to_borrow:
    self.MarketOrder(self.symbol, -quantity_to_borrow)</pre>
</div>


<h4>Invalid New Order Status</h4>
<p>The <code>OrderResponseErrorCode.InvalidNewOrderStatus</code> (-32) error occurs in live trading when you try to update or cancel an order while it still has <code>OrderStatus.New</code> status.</p>

<p>To avoid this order response error, check the <code>Status</code> property of the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets'>order ticket</a> or <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> before you update or cancel an order.</p>

<a id='european-option-not-expired-on-exercise'></a><div class="section-example-container">
    <pre class="csharp">if (_orderTicket.Status != OrderStatus.New)
{
    _orderTicket.Cancel();
}</pre>
    <pre class="python">if self.order_ticket.Status != OrderStatus.New:
    self.order_ticket.Cancel()</pre>
</div>

<h4>European Option Not Expired on Exercise</h4>
<p>The <code>OrderResponseErrorCode.EuropeanOptionNotExpiredOnExercise</code> (-33) error occurs when you try to exercise a European Option contract before its expiry date.</p>

<p>To avoid this order response error, check the type and expiry date of the contract before you exercise it.</p>

<a id='option-order-on-stock-split'></a><div class="section-example-container">
    <pre class="csharp">if (_contractSymbol.ID.OptionStyle == OptionStyle.European && _contractSymbol.ID.Date == Time.Date)
{
    ExerciseOption(_contractSymbol, quantity);
}</pre>
    <pre class="python">if self.contract_symbol.ID.OptionStyle == OptionStyle.European && self.contract_symbol.ID.Date == self.Time.Date:
    self.ExerciseOption(self.contract_symbol, quantity)</pre>
</div>


<h4>Option Order on Stock Split</h4>
<p>The <code>OrderResponseErrorCode.OptionOrderOnStockSplit</code> (-34) error occurs when you try to submit an order for an Equity Option contract when the current <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time slice</a> contains a <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits'>split</a> for the underlying Equity.</p>

<p>To avoid this order response error, check if the time slice has a split event for the underlying Equity of the contract before you place an order for the contract.</p>

<div class="section-example-container">
    <pre class="csharp">if (!slice.Splits.ContainsKey(_contractSymbol.Underlying))
{
    MarketOrder(_contractSymbol, quantity);
}</pre>
    <pre class="python">if self.contract_symbol.Underlying not in slice.Splits:
    self.MarketOrder(self.contract_symbol, quantity)</pre>
</div>
