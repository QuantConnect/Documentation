<p>The following examples demonstrate some common practices for implementing the framework Option Universe Selection Model.</p>

<h4>Example 1: Horizontal Jelly Roll</h4>
<p>The following algorithm selects SPX index options to construct a Jelly Roll strategy. It filters for ATM calls and puts with 30 days and 90 days till expiration. Using the SMA indicator to predict the interest rate cycle, it longs Jelly Roll if the cycle is considered uprising, otherwise selling the Jelly Roll.</p>
<div class="section-example-container testable">
    <pre class="csharp">public class FrameworkOptionUniverseSelectionAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2023, 1, 1);
        SetEndDate(2023, 8, 1);
        
        // Add a universe that selects the needed option contracts.
        AddUniverseSelection(new AtmOptionHorizontalSpreadUniverseSelectionModel());
        // Add Alpha model to trade Jelly Roll, using interest rate data.
        AddAlpha(new JellyRollAlphaModel(this));
        // Invest in the same number of contracts per leg in the Jelly Roll.
        SetPortfolioConstruction(new SingleSharePortfolioConstructionModel());
    }
    
    private class AtmOptionHorizontalSpreadUniverseSelectionModel : OptionUniverseSelectionModel
    {
        // 30d update with the SelectOptionChainSymbols function since the filter returns at least 30d expiry options.
        public AtmOptionHorizontalSpreadUniverseSelectionModel()
                : base(TimeSpan.FromDays(30), SelectOptionChainSymbols) {}
        
        private static IEnumerable&lt;Symbol&gt; SelectOptionChainSymbols(DateTime utcTime)
        {
            // We will focus only on SPX options since they have a relatively stable dividend yield, which we assume will remain the same over time.
            // Also, assignment handling is not required since it is cash-settled.
            return new[] {QuantConnect.Symbol.Create("SPX", SecurityType.IndexOption, Market.USA)};
        }

        protected override OptionFilterUniverse Filter(OptionFilterUniverse filter)
        {
            // To trade interest rates using options, Jelly Roll is one of the best strategies.
            // It is market-neutral but sensitive to interest rate and dividend yield changes.
            // We target to trade the market speculation between 30d and 90d options interest rate.
            return filter.JellyRoll(0, 30, 90);
        }
    }

    private class JellyRollAlphaModel : AlphaModel
    {
        private QCAlgorithm _algorithm;
        private Symbol _symbol = QuantConnect.Symbol.Create("SPX", SecurityType.IndexOption, Market.USA);
        private bool _wasRising;
        // Use a 365d SMA indicator of daily interest rate to estimate if the interest rate cycle is upward or downward.
        private SimpleMovingAverage _sma = new(365);

        public JellyRollAlphaModel(QCAlgorithm algorithm)
        {
            _algorithm = algorithm;

            // Warm up the SMA indicator.
            var current = algorithm.Time;
            var provider = algorithm.RiskFreeInterestRateModel;
            for (DateTime dt = current.AddDays(-365); dt &lt;= current; dt = dt.AddDays(1))
            {
                var rate = provider.GetInterestRate(dt);
                _sma.Update(dt, rate);
                _wasRising = rate &gt; _sma;
            }

            // Set a schedule to update the interest rate trend indicator daily.
            algorithm.Schedule.On(
                algorithm.DateRules.EveryDay(),
                algorithm.TimeRules.At(0, 1),
                UpdateInterestRate
            );
        }

        private void UpdateInterestRate()
        {
            // Update the interest rate on the SMA indicator to estimate its trend.
            var rate = _algorithm.RiskFreeInterestRateModel.GetInterestRate(_algorithm.Time);
            _sma.Update(_algorithm.Time, rate);
            _wasRising = rate &gt; _sma;
        }

        public override IEnumerable&lt;Insight&gt; Update(QCAlgorithm algorithm, Slice slice)
        {
            var insights = new List&lt;Insight&gt;();
            
            // Hold one position group at a time.
            if (algorithm.Portfolio.Invested || !slice.OptionChains.TryGetValue(_symbol, out var chain))
            {
                return insights;
            }
            
            // Obtain the Jelly Roll constituents from the option chain.
            var calls = chain.Where(x =&gt; x.Right == OptionRight.Call)
                .OrderBy(x =&gt; x.Expiry)
                .ToList();
            var puts = chain.Where(x =&gt; x.Right == OptionRight.Put)
                .OrderBy(x =&gt; x.Expiry)
                .ToList();
            var nearCall = calls[0];
            var farCall = calls[^1];
            var nearPut = puts[0];
            var farPut = puts[^1];

            // Emit insight of the Jelly Roll constituents, with directions depending on the interest rate trend given by SMA.
            var rate = algorithm.RiskFreeInterestRateModel.GetInterestRate(algorithm.Time);
            List&lt;Insight&gt; insightGroup;
            // During the rising interest rate cycle, order a long Jelly Roll.
            if (rate &gt; _sma)
            {
                insightGroup = new() {
                    Insight.Price(nearCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(farCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(nearPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(farPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Down)
                };
            }
            // During a downward interest rate cycle, order short Jelly Roll.
            else if (rate &lt; _sma)
            {
                insightGroup = new() {
                    Insight.Price(nearCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(farCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(nearPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(farPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Up)
                };
            }
            // If the interest rate cycle remains steady for a long time, we expect a flip in the cycle soon.
            else if (_wasRising)
            {
                insightGroup = new() {
                    Insight.Price(nearCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(farCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(nearPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(farPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Up)
                };
            }
            else
            {
                insightGroup = new() {
                    Insight.Price(nearCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Down),
                    Insight.Price(farCall.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(nearPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Up),
                    Insight.Price(farPut.Symbol, TimeSpan.FromDays(30), InsightDirection.Down)
                };
            }
            insights.AddRange(insightGroup);

            return insights;
        }
    }
    
    private class SingleSharePortfolioConstructionModel : PortfolioConstructionModel
    {
        public override IEnumerable&lt;PortfolioTarget&gt; CreateTargets(QCAlgorithm algorithm, Insight[] insights)
        {
            var targets = new List&lt;PortfolioTarget&gt;();
            foreach (var insight in insights)
            {
                if (algorithm.Securities[insight.Symbol].IsTradable)
                {
                    // Use integer target to create a portfolio target to trade a single contract
                    targets.Add(new PortfolioTarget(insight.Symbol, (int) insight.Direction));
                }
            }
            return targets;
        }
    }
}</pre>
    <pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel

class FrameworkOptionUniverseSelectionAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2023, 1, 1)
        self.set_end_date(2023, 8, 1)

        # Add a universe that selects the needed option contracts.
        self.add_universe_selection(AtmOptionHorizontalSpreadUniverseSelectionModel())
        # Add Alpha model to trade Jelly Roll, using interest rate data.
        self.add_alpha(JellyRollAlphaModel(self))
        # Invest in the same number of contracts per leg in the Jelly Roll.
        self.set_portfolio_construction(SingleSharePortfolioConstructionModel())
        
class AtmOptionHorizontalSpreadUniverseSelectionModel(OptionUniverseSelectionModel):
    # 30d update with the SelectOptionChainSymbols function since the filter returns at least 30d expiry options.
    def __init__(self) -> None:
        super().__init__(timedelta(30), self.selection_option_chain_symbols)

    def selection_option_chain_symbols(self, utc_time: datetime) -> list[Symbol]:
        # We will focus only on SPX options since they have a relatively stable dividend yield, which we assume will remain the same over time.
        # Also, assignment handling is not required since it is cash-settled.
        return [Symbol.create("SPX", SecurityType.INDEX_OPTION, Market.USA)]

    def filter(self, filter: OptionFilterUniverse) -> OptionFilterUniverse:
        # Jelly Roll is one of the best strategies for trading interest rates using options.
        # It is market-neutral but sensitive to interest rate and dividend yield changes.
        # We target to trade the market speculation between 30d and 90d options interest rate.
        return filter.jelly_roll(0, 30, 90)

class JellyRollAlphaModel(AlphaModel):
    _symbol = Symbol.create("SPX", SecurityType.INDEX_OPTION, Market.USA)
    # Use a 365d SMA indicator of daily interest rate to estimate if the interest rate cycle is upward or downward.
    _sma = SimpleMovingAverage(365)

    def __init__(self, algorithm: QCAlgorithm) -> None:
        self._algorithm = algorithm

        # Warm up the SMA indicator.
        current = algorithm.time
        provider = algorithm.risk_free_interest_rate_model
        dt = current - timedelta(365)
        while dt &lt;= current:
            rate = provider.get_interest_rate(dt)
            self._sma.update(dt, rate)
            self._was_rising = rate > self._sma.current.value
            dt += timedelta(1)
        
        # Set a schedule to update the interest rate trend indicator every day.
        algorithm.schedule.on(
            algorithm.date_rules.every_day(),
            algorithm.time_rules.at(0, 1),
            self.update_interest_rate
        )

    def update_interest_rate(self) -> None:
        # Update interest rate to the SMA indicator to estimate its trend.
        rate = self._algorithm.risk_free_interest_rate_model.get_interest_rate(self._algorithm.time)
        self._sma.update(self._algorithm.time, rate)
        self._was_rising = rate > self._sma.current.value

    def update(self, algorithm: QCAlgorithm, slice: Slice) -> list[Insight]:
        insights = []

        # Hold one position group at a time.
        chain = slice.option_chains.get(self._symbol)
        if algorithm.portfolio.invested or not chain:
            return insights

        # Obtain the Jelly Roll constituents from the option chain.
        calls = sorted([x for x in chain if x.right == OptionRight.CALL], key=lambda x: x.expiry)
        puts = sorted([x for x in chain if x.right == OptionRight.PUT], key=lambda x: x.expiry)
        near_call = calls[0]
        far_call = calls[-1]
        near_put = puts[0]
        far_put = puts[-1]

        # Emit insight of the Jelly Roll constituents, with directions depending on the interest rate trend given by SMA.
        rate = algorithm.risk_free_interest_rate_model.get_interest_rate(algorithm.time)
        # During the rising interest rate cycle, order a long Jelly Roll.
        if rate > self._sma.current.value:
            insights.extend([
                Insight.price(near_call.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(far_call.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(near_put.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(far_put.symbol, timedelta(30), InsightDirection.DOWN)
            ])
        # During the downward interest rate cycle, order short Jelly Roll.
        elif rate &lt; self._sma.current.value:
            insights.extend([
                Insight.price(near_call.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(far_call.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(near_put.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(far_put.symbol, timedelta(30), InsightDirection.UP)
            ])
        # If the interest rate cycle is steady for a long, we expect a flip in the cycle coming up.
        elif self._was_rising:
            insights.extend([
                Insight.price(near_call.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(far_call.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(near_put.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(far_put.symbol, timedelta(30), InsightDirection.UP)
            ])
        else:
            insights.extend([
                Insight.price(near_call.symbol, timedelta(30), InsightDirection.DOWN),
                Insight.price(far_call.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(near_put.symbol, timedelta(30), InsightDirection.UP),
                Insight.price(far_put.symbol, timedelta(30), InsightDirection.DOWN)
            ])
        
        return insights

class SingleSharePortfolioConstructionModel(PortfolioConstructionModel):
    def create_targets(self, algorithm: QCAlgorithm, insights: list[Insight]) -> list[PortfolioTarget]:
        targets = []
        for insight in insights:
            if algorithm.securities[insight.symbol].is_tradable:
                # Use integer target to create a portfolio target to trade a single contract
                targets.append(PortfolioTarget(insight.symbol, insight.direction))
        return targets</pre>
</div>

<p>
    The following example chains a <a href='/docs/v2/writing-algorithms/universes/equity/fundamental-universes'>fundamental universe</a> and an <a href='/docs/v2/writing-algorithms/universes/equity-options'>Equity Options universe</a>.
    It first selects 10 stocks with the lowest PE ratio and then selects their front-month call Option contracts.
    It buys one front-month call Option contract every day.
</p>

<p>To override the default <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>pricing model</a> of the Options, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#03-Set-Models'>set a pricing model</a> in a security initializer.</p>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<div class="section-example-container testable">
	<pre class="csharp">// Example code to chain a fundamental universe and an Equity Options universe by selecting top 10 stocks with lowest PE, indicating potentially undervalued stocks and then selecting their from-month call Option contracts to target contracts with high liquidity.
public class ETFUniverseOptions : QCAlgorithm
{
    private int _day;
    public override void Initialize()
    {
        SetStartDate(2023, 2, 2);
        SetCash(100000);
        UniverseSettings.Asynchronous = true;
        UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
        SetSecurityInitializer(new CustomSecurityInitializer(this));

        var universe = AddUniverse(FundamentalFunction);
        AddUniverseOptions(universe, OptionFilterFunction);
    }

    private IEnumerable&lt;Symbol&gt; FundamentalFunction(IEnumerable&lt;Fundamental&gt; fundamental)
    {
        return fundamental
            .Where(f =&gt; !double.IsNaN(f.ValuationRatios.PERatio))
            .OrderBy(f =&gt; f.ValuationRatios.PERatio)
            .Take(10)
            .Select(x =&gt; x.Symbol);
    }

    private OptionFilterUniverse OptionFilterFunction(OptionFilterUniverse optionFilterUniverse)
    {
        return optionFilterUniverse.Strikes(-2, +2).FrontMonth().CallsOnly();
    }

    public override void OnData(Slice data)
    {
        if (IsWarmingUp || _day == Time.Day) return;

        foreach (var (symbol, chain) in data.OptionChains)
        {
            if (Portfolio[chain.Underlying.Symbol].Invested)
                Liquidate(chain.Underlying.Symbol);

            var spot = chain.Underlying.Price;
            var contract = chain.OrderBy(x =&gt; Math.Abs(spot-x.Strike)).FirstOrDefault();
            var tag = $"IV: {contract.ImpliedVolatility:F3} Δ: {contract.Greeks.Delta:F3}";
            MarketOrder(contract.Symbol, 1, true, tag);
            _day = Time.Day;
        }
    }
}

internal class CustomSecurityInitializer : BrokerageModelSecurityInitializer
{
    private QCAlgorithm _algorithm;
    public CustomSecurityInitializer(QCAlgorithm algorithm)
        : base(algorithm.BrokerageModel, new FuncSecuritySeeder(algorithm.GetLastKnownPrices))
    {
        _algorithm = algorithm;
    }    

    public override void Initialize(Security security)
    {
        // First, call the superclass definition
        // This method sets the reality models of each security using the default reality models of the brokerage model
        base.Initialize(security);

        // Next, overwrite the price model        
        if (security.Type == SecurityType.Option) // Option type
        {
            (security as Option).PriceModel = OptionPriceModels.CrankNicolsonFD();
        }

        // Overwrite the volatility model and warm it up
        if (security.Type == SecurityType.Equity)
        {
            security.VolatilityModel = new StandardDeviationOfReturnsVolatilityModel(30);
            var tradeBars = _algorithm.History(security.Symbol, 30, Resolution.Daily);
            foreach (var tradeBar in tradeBars)
                security.VolatilityModel.Update(security, tradeBar);
        }    
    }
}</pre>
	<pre class="python"># Example code to chain a fundamental universe and an Equity Options universe by selecting top 10 stocks with lowest PE, indicating potentially undervalued stocks and then selecting their from-month call Option contracts to target contracts with high liquidity.
from AlgorithmImports import *

    class ChainedUniverseAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2023, 2, 2)
        self.set_cash(100000)
        self.universe_settings.asynchronous = True
        self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
        self.set_security_initializer(CustomSecurityInitializer(self))

        universe = self.add_universe(self.fundamental_function)
        self.add_universe_options(universe, self.option_filter_function)
        self.day = 0

    def fundamental_function(self, fundamental: list[Fundamental]) -&gt; list[Symbol]:
        filtered = (f for f in fundamental if not np.isnan(f.valuation_ratios.pe_ratio))
        sorted_by_pe_ratio = sorted(filtered, key=lambda f: f.valuation_ratios.pe_ratio)
        return [f.symbol for f in sorted_by_pe_ratio[:10]]

    def option_filter_function(self, option_filter_universe: OptionFilterUniverse) -&gt; OptionFilterUniverse:
        return option_filter_universe.strikes(-2, +2).front_month().calls_only()

    def on_data(self, data: Slice) -&gt; None:
        if self.is_warming_up or self.day == self.time.day:
            return
        
        for symbol, chain in data.option_chains.items():
            if self.portfolio[chain.underlying.symbol].invested:
                self.liquidate(chain.underlying.symbol)

            spot = chain.underlying.price
            contract = sorted(chain, key=lambda x: abs(spot-x.strike))[0]
            tag = f"IV: {contract.implied_volatility:.3f} Δ: {contract.greeks.delta:.3f}"
            self.market_order(contract.symbol, 1, True, tag)
            self.day = self.time.day

class CustomSecurityInitializer(BrokerageModelSecurityInitializer):
    def __init__(self, algorithm: QCAlgorithm) -&gt; None:
        super().__init__(algorithm.brokerage_model, FuncSecuritySeeder(algorithm.get_last_known_prices))
        self.algorithm = algorithm

    def initialize(self, security: Security) -&gt; None:
        # First, call the superclass definition
        # This method sets the reality models of each security using the default reality models of the brokerage model
        super().initialize(security)

        # Overwrite the price model        
        if security.type == SecurityType.OPTION: # Option type
            security.price_model = OptionPriceModels.crank_nicolson_fd()

        # Overwrite the volatility model and warm it up
        if security.type == SecurityType.EQUITY:
            security.volatility_model = StandardDeviationOfReturnsVolatilityModel(30)
            trade_bars = self.algorithm.history[TradeBar](security.symbol, 30, Resolution.DAILY)
            for trade_bar in trade_bars:
                security.volatility_model.update(security, trade_bar)</pre>
</div>
