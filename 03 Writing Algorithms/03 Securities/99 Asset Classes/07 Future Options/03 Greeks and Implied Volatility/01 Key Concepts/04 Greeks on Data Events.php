<p>
  The Greeks and IV values that you get from the current <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> are from the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a> calculations.
  These values aren't the same as the values you get in the universe filter function.
</p>

<div class="section-example-container">
    <pre class="csharp">public class FutureOptionDataEventsAlgorithm : QCAlgorithm
{
    private Future _future;

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
        SetSecurityInitializer(
            new MySecurityInitializer(BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices))
        );
        _future = AddFuture(Futures.Indices.SP500EMini, 
            resolution: Resolution.Minute, 
            fillForward: true, 
            extendedMarketHours: true,
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            dataMappingMode: DataMappingMode.OpenInterest,
            contractDepthOffset: 0);
        _future.SetFilter(0, 182);
        AddFutureOption(_future.Symbol, universe => universe.Strikes(-5, 5));
    }

    public override void OnData(Slice data)
    {
        foreach (var chain in data.OptionChains.Values)
        {
            foreach (var contract in chain)
            {
                var iv = contract.ImpliedVolatility;
                var delta = contract.Greeks.Delta;
            }
        }
    }
}

class MySecurityInitializer : BrokerageModelSecurityInitializer
{
    public MySecurityInitializer(IBrokerageModel brokerageModel, ISecuritySeeder securitySeeder)
        : base(brokerageModel, securitySeeder) {}    
    
    public override void Initialize(Security security)
    {
        base.Initialize(security);
        if (security.Type == SecurityType.FutureOption)
        {
            (security as Option).PriceModel = OptionPriceModels.CrankNicolsonFD();
        }    
    }
}</pre>
    <pre class="python">class BasicFutureOptionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_security_initializer(
            MySecurityInitializer(self.brokerage_model, FuncSecuritySeeder(self.get_last_known_prices))
        )
        self._future = self.add_future(
            Futures.Indices.SP_500_E_MINI,
            extended_market_hours=True,
            data_mapping_mode=DataMappingMode.OPEN_INTEREST,
            data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
            contract_depth_offset=0
        )
        self._future.set_filter(0, 182)
        self.add_future_option(self._future.symbol, lambda universe: universe.strikes(-5, 5))
    
    def on_data(self, data):
        for chain in data.option_chains.values():
            for contract in chain:
                iv = contract.implied_volatility
                delta = contract.greeks.delta


class MySecurityInitializer(BrokerageModelSecurityInitializer):

    def __init__(self, brokerage_model: IBrokerageModel, security_seeder: ISecuritySeeder) -> None:
        super().__init__(brokerage_model, security_seeder)
    
    def initialize(self, security: Security) -> None:
        super().initialize(security)
        if security.type == SecurityType.FUTURE_OPTION:
            security.price_model = OptionPriceModels.CrankNicolsonFD()</pre>
</div>

<p>To view all the models LEAN supports, see <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#06-Supported-Models'>Supported Models</a>.</p>
