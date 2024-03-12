<p>When you place Crypto trades, don't use the <code>CalculateOrderQuantity</code> or <code>SetHoldings</code> methods. Instead, calculate the order quantity based on the currency amounts in your <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cash book</a> and place manual orders.</p>

<p>The following code snippet demonstrates how to allocate 90% of your portfolio to BTC.</p>

<div class="section-example-container">
<pre class="csharp">public override void OnData(Slice data)
{
    SetCryptoHoldings(_symbol, 0.9m);
}

private void SetCryptoHoldings(Symbol symbol, decimal percentage)
{
    var crypto = Securities[symbol] as Crypto;
    var baseCurrency = crypto.BaseCurrency;

    // Calculate the target quantity in the base currency
    var targetQuantity = percentage * (Portfolio.TotalPortfolioValue - Settings.FreePortfolioValue) / baseCurrency.ConversionRate;
    var quantity = targetQuantity - baseCurrency.Amount;

    // Round down to observe the lot size
    var lotSize = crypto.SymbolProperties.LotSize;
    quantity = Math.Round(quantity / lotSize) * lotSize;

    if (IsValidOrderSize(crypto, quantity))
    {
        MarketOrder(symbol, quantity);
    }
}

// Brokerages have different order size rules
// Binance considered the minimum volume (price x quantity):
private bool IsValidOrderSize(Crypto crypto, decimal quantity)
{
    return Math.Abs(crypto.Price * quantity) > crypto.SymbolProperties.MinimumOrderSize;
}</pre>
<pre class="python">def OnData(self, data: Slice):
    self.set_crypto_holdings(self.symbol, .9)

def set_crypto_holdings(self, symbol, percentage):
    crypto = self.Securities[symbol]
    base_currency = crypto.BaseCurrency

    # Calculate the target quantity in the base currency
    target_quantity = percentage * (self.Portfolio.TotalPortfolioValue - self.Settings.FreePortfolioValue) / base_currency.ConversionRate    
    quantity = target_quantity - base_currency.Amount

    # Round down to observe the lot size
    lot_size = crypto.SymbolProperties.LotSize
    quantity = round(quantity / lot_size) * lot_size

    if self.is_valid_order_size(crypto, quantity):
        self.MarketOrder(symbol, quantity)

# Brokerages have different order size rules
# Binance considers the minimum volume (price x quantity):
def is_valid_order_size(self, crypto, quantity):
    return abs(crypto.Price * quantity) > crypto.SymbolProperties.MinimumOrderSize
</pre>
</div>

<p>The preceding example doesn't take into account order fees. You can add a 0.1% buffer to accommodate it.</p>

<p>The following example demonstrates how to form an equal-weighted Crypto portfolio and stay within the <a href="/docs/v2/writing-algorithms/trading-and-orders/position-sizing#05-Buying-Power-Buffer">cash buffer</a>. <br></p>

<div class="section-example-container">
<pre class="csharp">
public override void OnData(Slice data)
{
    var percentage = (1m - Settings.FreePortfolioValuePercentage) / _symbols.Count;
    foreach (var symbol in _symbols)
    {
        SetCryptoHoldings(_symbol, percentage);
    }
}</pre>
<pre class="python">
def OnData(self, data: Slice):
    percentage = (1 - self.Settings.FreePortfolioValuePercentage) / len(self.symbols);
    for symbol in self.symbols:
        self.set_crypto_holdings(symbol, percentage)</pre>
</div>

<p>You can replace the <code class="python">self.</code><code>Settings.FreePortfolioValuePercentage</code> for a class variable (e.g. <code class="python">self.cash_buffer</code><code class="csharp">_cashBuffer</code>).</p>

<? echo file_get_contents(DOCS_RESOURCES."/order-types/crypto-insufficient-buying-power.html"); ?>

<p>For a full example of placing crypto trades, see the <span class="python"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateCryptoAlgorithm.py">BasicTemplateCryptoAlgorithm</a></span><span class="csharp"><a href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateCryptoAlgorithm.cs">BasicTemplateCryptoAlgorithm</a></span>.</p>