<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">public class Automatic<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    <?=$memberDeclarationsAutomaticC?>
    private List&lt;<?=$typeName?>&gt; _indicators = new();

    public override void Initialize()
    {
        // Subscribe to the underlying asset.
        <?=$underlyingSubscriptionC?>
    
        // Set up a Scheduled Event to select contracts and create the indicators every day before market open.
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
                <?=$addContractMethodC?>(call.Symbol);
                <?=$addContractMethodC?>(put.Symbol);

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
        <?=$underlyingSubscriptionPy?>

        # Set up a Scheduled Event to select contracts and create the indicators every day before market open.
        self.schedule.on(
            self.date_rules.every_day(<?=$scheduleSymbolPy?>),
            self.time_rules.at(9, 0),
            self._update_contracts_and_greeks
        )

    def _update_contracts_and_greeks(self) -&gt; None:
        # Get all the tradable Option contracts.
        chain = self.option_chain(<?=$underlyingSymbolPy?>).data_frame
        if chain.empty:
            return

        # Filter the contracts down. For example, ATM contracts with the closest expiry.
        expiry = chain.expiry.min()
        chain = chain[chain.expiry == expiry]
        chain.loc[:, 'abs_strike_delta'] = abs(chain['strike'] - chain['underlyinglastprice'])
        abs_strike_delta = chain['abs_strike_delta'].min()
        chain = chain[chain['abs_strike_delta'] == abs_strike_delta]

        # Group the contracts into call/put pairs.
        contracts_pair_sizes = chain.groupby(['expiry', 'strike']).count()['right']
        paired_contracts = contracts_pair_sizes[contracts_pair_sizes == 2].index
        symbols = chain[
            chain['expiry'].isin(paired_contracts.levels[0]) & 
            chain['strike'].isin(paired_contracts.levels[1])
        ].reset_index().groupby(['expiry', 'strike', 'right', 'symbol']).first().index.levels[-1]
        pairs = [(symbols[i], symbols[i+1]) for i in range(0, len(symbols), 2)]

        for call, put in pairs:
            # Subscribe to both contracts.
            self.<?=$addContractMethodPy?>(call)
            self.<?=$addContractMethodPy?>(put)
            
            # Create and save the automatic <?=$typeName?> indicators.
            self._indicators.extend([self.<?=strtolower($helperMethod)?>(call, put), self.<?=strtolower($helperMethod)?>(put, call)])
        
    def on_data(self, slice: Slice) -&gt; None:
        # Get the <?=$typeName?> indicator of each contract.
        for indicator in self._indicators:
            symbol = indicator.option_symbol
            value = indicator.current.value</pre>
</div>
