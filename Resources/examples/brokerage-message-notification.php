<h4>Example <?=$number?>: Pre-Open Order Error</h4>
<p>The following algorithm simulate extended market hour trading on <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/tradier">Tradier Brokerage</a>. We place a market-on-open order at 7:30am and expect the order cannot submit to the broker since it is not supported, resulting into an error.</p>
<div class="section-example-container testable">
    <pre class="csharp">public class OrderErrorsAlgorithm : QCAlgorithm
{
    private Symbol _spy;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2022, 1, 5);
        // Simulate Tradier brokerage, which does not support the market-on-open orders.
        SetBrokerageModel(BrokerageName.TradierBrokerage, AccountType.Cash);

        // Request extended market hour SPY data for trading.
        _spy = AddEquity("SPY", extendedMarketHours: true).Symbol;

        // Set a scheduled event to trade 2 hours pre-open to place the market-on-open-order.
        Schedule.On(
            DateRules.EveryDay(_spy),
            TimeRules.BeforeMarketOpen(_spy, 120),
            OpenPosition
        );
    }

    private void OpenPosition()
    {
        // Buy on market open will result in an error since Tradier does not support it.
        MarketOnOpenOrder(_spy, 10);
    }

    // It will be triggered on live trading.
    public override void OnBrokerageMessage(BrokerageMessageEvent message)
    {
        if (message.Type == BrokerageMessageType.Error)
        {
            Log($"{Time}: {message.Type}: Message: {message.Message}");
        }
    }
}</pre>
    <pre class="python">class OrderErrorsAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2022, 1, 5)
        # Simulate Tradier brokerage, which does not support the market-on-open orders.
        self.set_brokerage_model(BrokerageName.TRADIER_BROKERAGE, AccountType.CASH)

        # Request extended market hour SPY data for trading.
        self.spy = self.add_equity("SPY", extended_market_hours=True).symbol

        # Set a scheduled event to trade 2 hours pre-open to place the market-on-open-order.
        self.schedule.on(
            self.date_rules.every_day(self.spy),
            self.time_rules.before_market_open(self.spy, 120),
            self.open_position
        )

    def open_position(self) -&gt; None:
        # Buy on market open will result in an error since Tradier does not support it.
        self.market_on_open_order(self.spy, 10)

    # It will be triggered on live trading.
    def on_brokerage_message(self, message: BrokerageMessageEvent) -&gt; None: 
        if message.type == BrokerageMessageType.ERROR:
            self.log(f"{self.time}: {message.type}: Message: {message.message}")</pre>
</div>
