<p>To create a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/manual-indicators'>manual indicator</a> for <?=$name?>, call the <code><?=$typeName?></code> constructor.</p>

<div class="section-example-container">
    <pre class="csharp">public class Manual<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    <?=$memberDeclarationsManualC?>
    // Create a list to store the indicators.
    private List&lt;<?=$typeName?>&gt; _indicators = new();
    // Define the Option pricing model.
    private readonly OptionPricingModelType _optionPricingModel = OptionPricingModelType.ForwardTree;

    public override void Initialize()
    {
        // Subscribe to the underlying asset.
        <?=$underlyingSubscriptionC?>

        // Set up dividend yield provider for the underlying
        _dividendYieldProvider = <?=$dividendYieldProviderConstructorC?>

        
        // Set up a Scheduled Event to select contract and create the indicators every day before market open.
        Schedule.On(
            DateRules.EveryDay(<?=$scheduleSymbolC?>),
            TimeRules.At(9, 0),
            UpdateContractsAndGreeks
        );
    }

    private void UpdateContractsAndGreeks()
    {
        // Get all the tradable Option contracts.
        var chain = OptionChain(<?=$underlyingSymbolC?>);
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
                _indicators.Add(new <?=$typeName?>(call.Symbol, RiskFreeInterestRateModel, _dividendYieldProvider, put.Symbol, _optionPricingModel));
                _indicators.Add(new <?=$typeName?>(put.Symbol, RiskFreeInterestRateModel, _dividendYieldProvider, call.Symbol, _optionPricingModel));
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Iterate through the indicators.
        foreach (var indicator in _indicators)
        {
            var option = indicator.OptionSymbol;
            var mirror = QuantConnect.Symbol.CreateOption(
                option.Underlying.Value, option.ID.Market, option.ID.OptionStyle, 
                option.ID.OptionRight == OptionRight.Call ? OptionRight.Put : OptionRight.Call, 
                option.ID.StrikePrice, option.ID.Date
            )<?=$mirrorExtensionC?>;
            
            // Check if price data is available for both contracts and the underlying asset.
            if (slice.QuoteBars.ContainsKey(option) && slice.QuoteBars.ContainsKey(mirror) && slice.Bars.ContainsKey(option.Underlying))
            {
                // Update the indicator.
                indicator.Update(new IndicatorDataPoint(option, slice.QuoteBars[option].EndTime, slice.QuoteBars[option].Close));
                indicator.Update(new IndicatorDataPoint(mirror, slice.QuoteBars[mirror].EndTime, slice.QuoteBars[mirror].Close));
                indicator.Update(new IndicatorDataPoint(option.Underlying, slice.Bars[option.Underlying].EndTime, slice.Bars[option.Underlying].Close));

                // Get the current value.
                var value = indicator.Current.Value;
            }
        }
    }
}</pre>
    <pre class="python">class Manual<?=$typeName?>IndicatorAlgorithm(QCAlgorithm):

    _indicators = []

    def initialize(self) -&gt; None:
        # Subscribe to the underlying asset.
        <?=$underlyingSubscriptionPy?>
        # Set up the dividend yield provider for the underlying.
        self._dividend_yield_provider = <?=$dividendYieldProviderConstructorPy?>

        # Define the Option pricing model.
        self._option_pricing_model = OptionPricingModelType.FORWARD_TREE

        # Set up a Scheduled Event to select contract and create the indicators every day before market open.
        self.schedule.on(
            self.date_rules.every_day(<?=$scheduleSymbolPy?>),
            self.time_rules.at(9, 0),
            self._update_contracts_and_greeks
        )
        
    def _update_contracts_and_greeks(self) -&gt; None:
        # Get all the tradable Option contracts.
        chain = self.option_chain(<?=$underlyingSymbolPy?>)
        # You can do further filtering here

        # Iterate all expiries
        for expiry in set(x.id.date for x in chain):
            contracts_for_expiry = [x for x in chain if x.id.date == expiry]
        
            # Iterate all strike prices among the contracts of the same expiry.
            for strike in set(x.id.strike_price for x in contracts_for_expiry):
                contracts_for_strike = [x for x in contracts_for_expiry if x.id.strike_price == strike]
            
                # Get the call and put, respectively.
                calls = [x for x in contracts_for_strike if x.id.option_right == OptionRight.CALL]
                puts = [x for x in contracts_for_strike if x.id.option_right == OptionRight.PUT]
                # Skip if the call doesn't exist, the put doesn't exist, or they are already in the universe.
                if not calls or not puts or calls[0].symbol in self.securities:
                    continue
                
                # Subscribe to both contracts.
                call = calls[0].symbol
                put = puts[0].symbol
                self.add_option_contract(call)
                self.add_option_contract(put)
            
                # Create and save the automatic <?=$typeName?> indicators.
                self._indicators.extend(
                    [
                        <?=$typeName?>(call, self.risk_free_interest_rate_model, self._dividend_yield_provider, put, self._option_pricing_model),
                        <?=$typeName?>(put, self.risk_free_interest_rate_model, self._dividend_yield_provider, call, self._option_pricing_model)
                    ]
                )

    def on_data(self, slice: Slice) -&gt; None:
        # Iterate through the indicators.
        for indicator in self._indicators:
            option = indicator.option_symbol
            mirror = Symbol.create_option(
                option.underlying.value, option.id.market, option.id.option_style, 
                OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT,
                option.id.strike_price, option.id.date
            )<?=$mirrorExtensionPy?>

            # Check if price data is available for both contracts and the underlying asset.
            if option in slice.quote_bars and mirror in slice.quote_bars and option.underlying in slice.bars:
                indicator.update(IndicatorDataPoint(option, slice.quote_bars[option].end_time, slice.quote_bars[option].close))
                indicator.update(IndicatorDataPoint(mirror, slice.quote_bars[mirror].end_time, slice.quote_bars[mirror].close))
                indicator.update(IndicatorDataPoint(option.underlying, slice.bars[option.underlying].end_time, slice.bars[option.underlying].close))

                # Get the current value.
                value = indicator.current.value</pre>
</div>

<p>For more information about the <code><?=$typeName?></code> constructor, see <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">Using <?=$helperMethod?> Indicator</a>.</p>
