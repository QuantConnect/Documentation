<p>
    The following example chains a <a href='/docs/v2/writing-algorithms/universes/equity/fundamental-universes'>fundamental universe</a> and an <a href='/docs/v2/writing-algorithms/universes/equity-options'>Equity Options universe</a>.
    It first selects 10 stocks with the lowest PE ratio and then selects their front-month call Option contracts.
    It buys one front-month call Option contract every day.
</p>

<p>To override the default <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>pricing model</a> of the Options, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a> in a security initializer.</p>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<div class="section-example-container">
	<pre class="csharp">using QuantConnect.Data;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.Securities;
using QuantConnect.Securities.Option;
using QuantConnect.Util;
using System;
using System.Collections.Generic;
using System.Linq;
namespace QuantConnect.Algorithm.CSharp
{
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

        private IEnumerable&ltSymbol&gt FundamentalFunction(IEnumerable&ltFundamental&gt fundamental)
        {
            return fundamental
                .Where(f =&gt !double.IsNaN(f.ValuationRatios.PERatio))
                .OrderBy(f =&gt f.ValuationRatios.PERatio)
                .Take(10)
                .Select(x =&gt x.Symbol);
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
                var contract = chain.OrderBy(x =&gt Math.Abs(spot-x.Strike)).FirstOrDefault();
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
    }
}</pre>
	<pre class="python">from AlgorithmImports import *

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

    def fundamental_function(self, fundamental: List[Fundamental]) -&gt List[Symbol]:
        filtered = (f for f in fundamental if not np.isnan(f.valuation_ratios.pe_ratio))
        sorted_by_pe_ratio = sorted(filtered, key=lambda f: f.valuation_ratios.pe_ratio)
        return [f.symbol for f in sorted_by_pe_ratio[:10]]

    def option_filter_function(self, option_filter_universe: OptionFilterUniverse) -&gt OptionFilterUniverse:
        return option_filter_universe.strikes(-2, +2).front_month().calls_only()

    def on_data(self, data: Slice) -&gt None:
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
    def __init__(self, algorithm: QCAlgorithm) -&gt None:
        super().__init__(algorithm.brokerage_model, FuncSecuritySeeder(algorithm.get_last_known_prices))
        self.algorithm = algorithm

    def initialize(self, security: Security) -&gt None:
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
