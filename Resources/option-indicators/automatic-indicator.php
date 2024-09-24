<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">public class Automatic<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    private Symbol _underlying;
    private List&lt;<?=$typeName?>&gt; _indicators = new();

    public override void Initialize()
    {
        // Subscribe to the underlying asset.
        _underlying = <?=$assetClass == "Equity" ? "AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw)" : "AddIndex(\"SPX\")"?>.Symbol;
    
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

                // Create and save the automatic <?=$typeName?> indicators.
                _indicators.Add(<?=$helperMethod?>(call.Symbol, put.Symbol));
                _indicators.Add(<?=$helperMethod?>(put.Symbol, call.Symbol));
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Get the <?=$typeName?> indicator of each contract.
        foreach (var indicator in _indicators)
        {
            var symbol = indicator.OptionSymbol;
            var value = indicator.Current.Value;
        }
    }
}</pre>
    <pre class="python">class Automatic<?=$typeName?>IndicatorAlgorithm(QCAlgorithm):
    _indicators = []
    
    def initialize(self) -&gt; None:
        # Subscribe to the underlying asset.
        self._underlying = <?=$assetClass == "Equity" ? "self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)" : "self.add_index('SPX')" ?>.symbol

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
                self._indicators.extend([self.<?=strtolower($helperMethod)?>(call, put), self.<?=strtolower($helperMethod)?>(put, call)])
        
    def on_data(self, slice: Slice) -&gt; None:
        # Get the <?=$typeName?> indicator of each contract.
        for indicator in self._indicators:
            symbol = indicator.option_symbol
            value = indicator.current.value</pre>
</div>
