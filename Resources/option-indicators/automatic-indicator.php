<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">public class Automatic<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    <?=$memberDeclarationsAutomaticC?>
    private List&lt;<?=$typeName?>&gt; _indicators = new();

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
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
        // Remove indicators on expired contracts.
        _indicators = _indicators.Where(indicator => indicator.OptionSymbol.ID.Date > Time).ToList();
        
        // Get all the tradable Option contracts.
        var chain = OptionChain(<?=$underlyingSymbolC?>);
        
        // Filter the contracts down. For example, ATM contracts with atleast 1 month until expiry.
        var filteredChain = chain.Where(contract => contract.Expiry > Time.AddDays(30));
        if (filteredChain.Count() == 0)
        {
            return;
        }
        var expiry = filteredChain.Min(contract => contract.Expiry);
        filteredChain = filteredChain
            .Where(contract => contract.Expiry == expiry)
            .OrderBy(contract => Math.Abs(contract.Strike - contract.UnderlyingLastPrice))
            .Take(4);

        // Group the contracts into call/put pairs.
        foreach (var group in filteredChain.GroupBy(contract => contract.Strike))
        {
            var contracts = group.ToList();
            if (contracts.Count > 1)
            {
                // Subscribe to both contracts.
                <?=$addContractMethodC?>(contracts[0]);
                <?=$addContractMethodC?>(contracts[1]);

                // Create and save the automatic <?=$typeName?> indicators.
                _indicators.Add(<?=$helperMethod?>(contracts[0], contracts[1]));
                _indicators.Add(<?=$helperMethod?>(contracts[1], contracts[0]));
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
        self.set_start_date(2024, 1, 1)
        # Subscribe to the underlying asset.
        <?=$underlyingSubscriptionPy?>

        # Set up a Scheduled Event to select contracts and create the indicators every day before market open.
        self.schedule.on(
            self.date_rules.every_day(<?=$scheduleSymbolPy?>),
            self.time_rules.at(9, 0),
            self._update_contracts_and_greeks
        )

    def _update_contracts_and_greeks(self) -&gt; None:
        # Remove indicators on expired contracts.
        self._indicators = [indicator for indicator in self._indicators if indicator.option_symbol.id.date > self.time]
        
        # Get all the tradable Option contracts.
        chain = self.option_chain(<?=$underlyingSymbolPy?>).data_frame
        if chain.empty:
            return

        # Filter the contracts down. For example, ATM contracts with atleast 1 month until expiry.
        expiry = chain.expiry.min()
        chain = chain[chain.expiry == expiry]
        chain.loc[:, 'abs_strike_delta'] = abs(chain['strike'] - chain['underlyinglastprice'])
        abs_strike_delta = chain['abs_strike_delta'].min()
        chain = chain[chain['abs_strike_delta'] == abs_strike_delta]

        # Group the contracts into call/put pairs.
        contracts_pair_sizes = chain.groupby(['expiry', 'strike']).count()['right']
        paired_contracts = contracts_pair_sizes[contracts_pair_sizes == 2].index
        expiries = [x[0] for x in paired_contracts]
        strikes = [x[1] for x in paired_contracts]
        symbols = [
            idx[-1] for idx in chain[
                chain['expiry'].isin(expiries) & chain['strike'].isin(strikes)
            ].reset_index().groupby(['expiry', 'strike', 'right', 'symbol']).first().index
        ]
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
