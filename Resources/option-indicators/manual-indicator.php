<p>To create a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/manual-indicators'>manual indicator</a> for <?=$name?>, call the <code><?=$typeName?></code> constructor.</p>

<div class="section-example-container">
    <pre class="csharp">public class Manual<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    private Symbol _underlying;
    private DividendYieldProvider _dividendYieldProvider;
    private List&lt;<?=strtolower($typeName)?>&gt; _indicators = new();
    // Define the Option pricing model.
    private readonly OptionPricingModelType _optionPricingModel = OptionPricingModelType.ForwardTree;

    public override void Initialize()
    {
        // Subscribe to the underlying asset.
        _underlying = <?=$assetClass == "Equity" ? "AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw)" : "AddIndex(\"SPY\")"?>.Symbol;
        // Set up dividend yield provider for the underlying
        _dividendYieldProvider = new(_underlying);
        
        // Set up a Scheduled Event to select contract and create the indicators every day before market open.
        Schedule.On(
            DateRules.EveryDay(_underlying),
            TimeRules.At(9, 0),
            UpdateContractsAndGreeks
        );
    }

    private void UpdateContractsAndGreeks()
    {
        // Get all the tradable Option contracts.
        var chain = OptionChain(_underlying);
        // You can do further filtering here
    
        // Iterate all expiries.
        foreach (var expiry in chain.Select(x =&gt; x.ID.Date).Distinct())
        {
            var contractsForExpiry = chain.Where(x =&gt; x.ID.Date == expiry).ToList();

            // Iterate all strike prices among the contracts of the same expiry.
            foreach (var strike in contractsForExpiry.Select(x =&gt; x.ID.StrikePrice).Distinct())
            {
                var contractsForStrike = contractsForExpiry.Where(x =&gt; x.ID.StrikePrice == strike).ToList();

                // Get the call and put, respectively.
                var call = contractsForStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Call);
                var put = contractsForStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Put);
                // Skip if the call doesn't exist, the put doesn't exist, or they are already in the universe.
                if (call == null || put == null || Securities.ContainsKey(call.Symbol)) continue;

                // Subscribe to both contracts.
                AddOptionContract(call.Symbol);
                AddOptionContract(put.Symbol);

                // Create and save the manual <?=$typeName?> indicators.
                _indicators.Add(new <?=$typeName?>(call, RiskFreeInterestRateModel, _dividendYieldProvider, put, _optionPricingModel));
                _indicators.Add(new <?=$typeName?>(put, RiskFreeInterestRateModel, _dividendYieldProvider, call, _optionPricingModel));
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Iterate through the indicators.
        foreach (var indicator in _indicators)
        {
            var option = indicator.OptionSymbol;
            var mirrorRight = option.ID.OptionRight == OptionRight.Call ? OptionRight.Put : OptionRight.Call;
            var mirror = QuantConnect.Symbol.CreateOption(option.Underlying.Value, Market.USA, OptionStyle.American, mirrorRight, option.ID.StrikePrice, option.ID.Date);
            
            // Check if price data is available for both contracts and the underlying asset.
            if (slice.QuoteBars.ContainsKey(option) && slice.QuoteBars.ContainsKey(mirror) && slice.Bars.ContainsKey(_underlying))
            {
                // Update the indicator.
                indicator.Update(new IndicatorDataPoint(option, slice.QuoteBars[option].EndTime, slice.QuoteBars[option].Close));
                indicator.Update(new IndicatorDataPoint(mirror, slice.QuoteBars[mirror].EndTime, slice.QuoteBars[mirror].Close));
                indicator.Update(new IndicatorDataPoint(_underlying, slice.Bars[_underlying].EndTime, slice.Bars[_underlying].Close));

                // Get the current value.
                var value = indicator.Current.Value;
            }
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>IndicatorAlgorithm(QCAlgorithm):
    _indicators = []

    def initialize(self) -&gt; None:
        # Subscribe to the underlying asset.
        self._underlying = <?=$assetClass == "Equity" ? "self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)" : "self.add_index('SPX')" ?>.symbol
        # Set up the dividend yield provider for the underlying.
        self._dividend_yield_provider = DividendYieldProvider(self._underlying)
        # Define the Option pricing model.
        self._option_pricing_model = OptionPricingModelType.FORWARD_TREE

        # Set up a Scheduled Event to select contract and create the indicators every day before market open.
        self.schedule.on(
            self.date_rules.every_day(self._underlying),
            self.time_rules.at(9, 0),
            self._update_contracts_and_greeks
        )
        
    def _update_contracts_and_greeks(self) -&gt; None:
        # Get all the tradable Option contracts.
        chain = self.option_chain(self._underlying)
        # You can do further filtering here

        # Iterate all expiries
        for expiry in set(x.id.date for x in chain):
            contracts_for_expiry = [x for x in chain if x.id.date == expiry]
        
            # Iterate all strike prices among the contracts of the same expiry.
            for strike in set(x.id.strike_price for x in contracts_for_expiry):
                contract_for_strike = [x for x in contracts_for_expiry if x.id.strike_price == strike]
            
                # Get the call and put respectively.
                call = next(filter(lambda x: x.id.option_right == OptionRight.CALL, contract_for_strike))
                put = next(filter(lambda x: x.id.option_right == OptionRight.PUT, contract_for_strike))
                # Skip if the call doesn't exist, the put doesn't exist, or they are already in the universe.
                if not call or not put or call.symbol in self.securities:
                    continue
                
                # Subscribe to both contracts.
                call = call.symbol
                put = put.symbol
                self.add_option_contract(call)
                self.add_option_contract(put)
            
                # Create and save the automatic <?=$typeName?> indicators.
                self._indicators.extend(
                    [
                        <?=$typeName?>(call, self._risk_free_interest_rate_model, self._dividend_yield_provider, put, self._option_pricing_model),
                        <?=$typeName?>(put, self._risk_free_interest_rate_model, self._dividend_yield_provider, call, self._option_pricing_model)
                    ]
                )

    def on_data(self, slice: Slice) -&gt; None:
        # Iterate through the indicators.
        for indicator in self._indicators:
            option = indicator.option_symbol
            mirror_right = OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT
            mirror = Symbol.create_option(option.underlying.value, Market.USA, OptionStyle.AMERICAN, mirror_right, option.id.strike_price, option.id.date)

            # Check if price data is available for both contracts and the underlying asset.
            if option in slice.quote_bars and mirror in slice.quote_bars and self.spy in slice.bars:
                indicator.update(IndicatorDataPoint(option, slice.quote_bars[option].end_time, slice.quote_bars[option].close))
                indicator.update(IndicatorDataPoint(mirror, slice.quote_bars[mirror].end_time, slice.quote_bars[mirror].close))
                indicator.update(IndicatorDataPoint(self.spy, slice.bars[self._underlying].end_time, slice.bars[self._underlying].close))

                # Get the current value.
                value = indicator.current.value</pre>
</div>

<p>For more information about the <code><?=$typeName?></code> constructor, see <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">Using <?=$helperMethod?> Indicator</a>.</p>
