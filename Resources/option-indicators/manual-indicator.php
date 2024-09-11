<p>To create a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/manual-indicators'>manual indicator</a> for <?=$name?>, call the <code><?=$typeName?></code> constructor.</p>

<div class="section-example-container">
    <pre class="csharp">private Symbol _spy;
private DividendYieldProvider _dividendYieldProvider;
private List&lt;Symbol&gt; _<?=strtolower($typeName)?>s = new();
// Define the option pricing model
private readonly OptionPricingModelType _optionPricingModel = OptionPricingModelType.ForwardTree;

public override void Initialize()
{
    // Subscribe to the underlying
    _spy = AddEquity("SPY", dataNormalizationMode=DataNormalizationMode.Raw).Symbol;
    // Set up dividend yield provider for the underlying
    _dividendYieldProvider = new(_spy);
    
    // Set up a scheduled event to select contract and create Greeks indicator daily before market open
    Schedule.On(
        DateRules.EveryDay(_spy),
        TimeRules.At(9, 0),
        UpdateContractsAndGreeks
    );
}

private void UpdateContractsAndGreeks()
{
    // Get all tradable option contracts
    var contractList = OptionChainProvider.GetOptionContractList(_spy, Time).ToList();
    // You can do further filtering here

    // Iterate all expiries
    foreach (var expiry in contractList.Select(x =&gt; x.ID.Date).Distinct())
    {
        var contractsByExpiry = contractList.Where(x =&gt; x.ID.Date == expiry).ToList();

        // Iterate all strike prices among the contracts of the same expiry
        foreach (var strike in contractsByExpiry.Select(x =&gt; x.ID.StrikePrice).Distinct())
        {
            var contractsByStrike = contractsByExpiry.Where(x =&gt; x.ID.StrikePrice == strike).ToList();

            // Get the call and put respectively
            var call = contractsByStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Call);
            var put = contractsByStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Put);
            // Skip if either call or put not exist
            if (call == null || put == null) continue;

            // Create subscriptions to both contract
            call = AddOptionContract(call).Symbol;
            put = AddOptionContract(put).Symbol;

            // Create the manual-updating <?=$typeName?> indicator
            var call<?=$typeName?> = new <?=$typeName?>(call, RiskFreeInterestRateModel, _dividendYieldProvider, put, _optionPricingModel);
            var put<?=$typeName?> = new <?=$typeName?>(put, RiskFreeInterestRateModel, _dividendYieldProvider, call, _optionPricingModel);
            // Add to list of indicator
            _<?=strtolower($typeName)?>s.Add(call<?=$typeName?>);
            _<?=strtolower($typeName)?>s.Add(put<?=$typeName?>);
        }
    }
}

public override void OnData(Slice slice)
{
    // Iterate indicators
    foreach (var <?=strtolower($typeName)?>Indicator in _<?=strtolower($typeName)?>s)
    {
        var option = <?=strtolower($typeName)?>Indicator.OptionSymbol;
        var mirrorRight = option.ID.OptionRight == OptionRight.Call ? OptionRight.Put : OptionRight.Call;
        var mirror = QuantConnect.Symbol.CreateOption(option.Underlying.Value, Market.USA, OptionStyle.American, mirrorRight, option.ID.StrikePrice, option.ID.Date);

        // Check if price data available for both contracts and the underlying
        if (slice.QuoteBars.ContainsKey(option) && slice.QuoteBars.ContainsKey(mirror) && slice.Bars.ContainsKey(_spy))
        {
            // Update the indicator
            <?=strtolower($typeName)?>Indicator.Update(new IndicatorDataPoint(option, slice.QuoteBars[option].EndTime, slice.QuoteBars[option].Close));
            <?=strtolower($typeName)?>Indicator.Update(new IndicatorDataPoint(mirror, slice.QuoteBars[mirror].EndTime, slice.QuoteBars[mirror].Close));
            <?=strtolower($typeName)?>Indicator.Update(new IndicatorDataPoint(_spy, slice.Bars[_spy].EndTime, slice.Bars[_spy].Close));

            // Get the current value
            var <?=strtolower($typeName)?> = <?=strtolower($typeName)?>Indicator.Current.Value;
        }
    }
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # List to hold Greeks indicators
    self.<?=strtolower($typeName)?>s = []

    # Subscribe to the underlying
    self.spy = self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol
    # Set up dividend yield provider for the underlying
    self.dividend_yield_provider = DividendYieldProvider(self.spy)
    # Define the option pricing model
    self.option_pricing_model = OptionPricingModelType.FORWARD_TREE

    # Set up a scheduled event to select contract and create Greeks indicator daily before market open
    self.schedule.on(
        self.date_rules.every_day(self.spy),
        self.time_rules.at(9, 0),
        self.update_contracts_and_greeks
    )

def update_contracts_and_greeks(self) -&gt; None:
    # Get all tradable option contracts
    contract_list = self.option_chain_provider.get_option_contract_list(self.spy, self.time)
    # You can do further filtering here

    # Iterate all expiries
    for expiry in set(x.id.date for x in contract_list):
        contract_by_expiry = [x for x in contract_list if x.id.date == expiry]
        
        # Iterate all strike prices among the contracts of the same expiry
        for strike in set(x.id.strike_price for x in contract_by_expiry):
            contract_by_strike = [x for x in contract_by_expiry if x.id.strike_price == strike]
        
            # Get the call and put respectively
            call = next(filter(lambda x: x.id.option_right == OptionRight.CALL, contract_by_strike))
            put = next(filter(lambda x: x.id.option_right == OptionRight.PUT, contract_by_strike))
            # Skip if either call or put not exist
            if not call or not put:
                continue
            
            # Create subscriptions to both contract
            call = self.add_option_contract(call).symbol
            put = self.add_option_contract(put).symbol
        
            # Create the manual-updating <?=$typeName?> indicator
            call_<?=strtolower($typeName)?> = <?=$typeName?>(call, self.risk_free_interest_rate_model, self.dividend_yield_provider, put, self.option_pricing_model)
            put_<?=strtolower($typeName)?> = <?=$typeName?>(put, self.risk_free_interest_rate_model, self.dividend_yield_provider, call, self.option_pricing_model)
            # Add to list of indicator
            self.<?=strtolower($typeName)?>s.extend([call_<?=strtolower($typeName)?>, put_<?=strtolower($typeName)?>])
        
def on_data(self, slice: Slice) -&gt; None:
    # Iterate indicators
    for <?=strtolower($typeName)?>_indicator in self.<?=strtolower($typeName)?>s:
        option = <?=strtolower($typeName)?>_indicator.option_symbol
        mirror_right = OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT
        mirror = Symbol.create_option(option.underlying.value, Market.USA, OptionStyle.AMERICAN, mirror_right, option.id.strike_price, option.id.date)
    
        # Check if price data available for both contracts and the underlying
        if option in slice.quote_bars and mirror in slice.quote_bars and self.spy in slice.bars:
            # Update the indicator
            <?=strtolower($typeName)?>_indicator.update(IndicatorDataPoint(option, slice.quote_bars[option].end_time, slice.quote_bars[option].close))</pre>
            <?=strtolower($typeName)?>_indicator.update(IndicatorDataPoint(mirror, slice.quote_bars[mirror].end_time, slice.quote_bars[mirror].close))
            <?=strtolower($typeName)?>_indicator.update(IndicatorDataPoint(self.spy, slice.bars[self.spy].end_time, slice.bars[self.spy].close))

            # Get the current value
            <?=strtolower($typeName)?> = <?=strtolower($typeName)?>_indicator.current.value;
</div>

<p>For more information about the <code><?=$typeName?></code> constructor, see <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">Using <?=$helperMethod?> Indicator</a>.</p>
