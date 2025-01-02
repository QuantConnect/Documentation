<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">public class Automatic<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    <?=$memberDeclarationsAutomaticC?>
    private (Symbol option1, Symbol option2) _options;

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
                var contract1 = <?=$addContractMethodC?>(contracts[0]) as dynamic;
                var contract2 = <?=$addContractMethodC?>(contracts[1]) as dynamic;

                // Create and save the automatic <?=$typeName?> indicators.
                contract1.<?=$helperMethod?> = <?=$helperMethod?>(contracts[0], contracts[1]);
                contract2.<?=$helperMethod?> = <?=$helperMethod?>(contracts[1], contracts[0]);

                _options = (contracts[0], contracts[1]);
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Get the <?=$typeName?> indicator of each contract.
        foreach (var (canonical, chain) in slice.OptionChain)
        {
            foreach (var symbol in chain)
            {
                var option = Securities[symbol] as dynamic;
                var indicator = option.<?=$helperMethod?>;
            }
        }

        // Sell straddle as an example to trade.
        if (!Portfolio.Invested)
        {
            Sell(_options.option1, 1);
            Sell(_options.option2, 1);
        }
        // Liquidate any assigned positions.
        if (Portfolio[_underlying].Invested)
        {
            Liquidate(_underlying);
        }
    }
}</pre>
    <pre class="python">class Automatic<?=$typeName?>IndicatorAlgorithm(QCAlgorithm):
    
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
        # Get all the tradable Option contracts.
        chain = self.option_chain(<?=$underlyingSymbolPy?>, flatten=True).data_frame
        if chain.empty:
            return

        # Filter the contracts down. For example, ATM contracts with atleast 1 month until expiry.
        chain = chain[chain.expiry > self.time + timedelta(30)]
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
            contract1 = self.<?=$addContractMethodPy?>(call)
            contract2 = self.<?=$addContractMethodPy?>(put)
            
            # Create and save the automatic <?=$typeName?> indicators.
            contract1.<?=strtolower($helperMethod)?> = self.<?=strtolower($helperMethod)?>(call, put)
            contract2.<?=strtolower($helperMethod)?> = self.<?=strtolower($helperMethod)?>(put, call)

            self._options = (call, put)
        
    def on_data(self, slice: Slice) -&gt; None:
        # Get the <?=$typeName?> indicator of each contract.
        for canonical, chain in slice.option_chain.items():
            for symbol in chain:
                option = self.securities[symbol]
                indicator = option.<?=strtolower($helperMethod)?>
        
        # Sell straddle as an example to trade.
        if not self.portfolio.invested:
            self.sell(self._options[0], 1)
            self.sell(self._options[1], 1)
        # Liquidate any assigned positions.
        if self.portfolio[self._underlying].invested:
            self.liquidate(self._underlying)</pre>
</div>
