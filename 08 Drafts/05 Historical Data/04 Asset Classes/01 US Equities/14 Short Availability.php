<p>To get historical values for the borrow fee rate, borrow rebate rate, or shortable quantity of a US Equity, call the <code class="csharp">FeeRate</code><code class="python">fee_rate</code>, <code class="csharp">RebateRate</code><code class="python">rebate_rate</code>, or <code class="csharp">ShortableQuantity</code><code class="python">shortable_quantity</code> methods of its <a href='/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts'>shortable provider</a>.</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityShortAvailabilityHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add an asset and save a reference to the Equity object.
        var security = AddEquity("SPY");
        // Overwrite the default shortable provider to one that provides data.
        security.SetShortableProvider(new InteractiveBrokersShortableProvider());
        // Select a time in the past.
        var t = Time.AddDays(-30);
        // Pass the time argument to shortable provider to get historical values.
        var feeRate = security.ShortableProvider.FeeRate(security.Symbol, t);
        var rebateRate = security.ShortableProvider.RebateRate(security.Symbol, t);
        var shortableQuantity = security.ShortableProvider.ShortableQuantity(security.Symbol, t);
    }
}</pre>
    <pre class="python">class USEquityShortAvailabilityHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add an asset and save a reference to its Equity object.
        security = self.add_equity('SPY')
        # Overwrite the default shortable provider to one that provides data.
        security.set_shortable_provider(InteractiveBrokersShortableProvider())
        # Select a time in the past.
        t = self.time - timedelta(30)
        # Pass the time argument to shortable provider to get historical values.
        fee_rate = security.shortable_provider.fee_rate(security.symbol, t)
        rebate_rate = security.shortable_provider.rebate_rate(security.symbol, t)
        shortable_quantity = security.shortable_provider.shortable_quantity(security.symbol, t)</pre>
</div>

<p>For more information about security initializers, see <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>Set Security Initializer</a>.</p>
