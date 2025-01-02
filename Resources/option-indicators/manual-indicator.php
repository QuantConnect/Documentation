<p>To create a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/manual-indicators'>manual indicator</a> for <?=$name?>, call the <code><?=$typeName?></code> constructor.</p>

<div class="section-example-container">
    <pre class="csharp">public class Manual<?=$typeName?>IndicatorAlgorithm : QCAlgorithm
{
    <?=$memberDeclarationsManualC?>
    // Define the Option pricing model.
    private readonly OptionPricingModelType _optionPricingModel = OptionPricingModelType.ForwardTree;
    private (Symbol option1, Symbol option2) _options;

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
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
                var contract1 = <?=$addContractMethodC?>(contracts[0]);
                var contract2 = <?=$addContractMethodC?>(contracts[1]);

                // Create and save the manual <?=$typeName?> indicators.
                foreach (var (contractA, contractB) in new[] { (contract1, contract2), (contract2, contract1) })
                {
                    var option = contractA as dynamic;
                    option.<?=$typeName?> = new <?=$typeName?>(
                            contractA.Symbol, RiskFreeInterestRateModel, _dividendYieldProvider, 
                            contractB.Symbol, _optionPricingModel
                        );
                }

                _options = (contracts[0], contracts[1]);
            }
        }
    }

    public override void OnData(Slice slice)
    {
        foreach (var (canonical, chain) in slice.OptionChain)
        {
            foreach (var option in chain)
            {
                var indicator = (Securities[option] as dynamic).<?=$typeName?>;
                var mirror = QuantConnect.Symbol.CreateOption(
                    option.Underlying.Value, option.ID.Market, option.ID.OptionStyle, 
                    option.ID.OptionRight == OptionRight.Call ? OptionRight.Put : OptionRight.Call, 
                    option.ID.StrikePrice, option.ID.Date
                )<?=$mirrorExtensionC?>;
                
                // Check if price data is available for both contracts and the underlying asset.
                var q = slice.QuoteBars;
                var b = slice.Bars;
                if (q.ContainsKey(option) && q.ContainsKey(mirror) && b.ContainsKey(option.Underlying))
                {
                    var dataPoints = new List&lt;IndicatorDataPoint&gt;
                    {
                        new IndicatorDataPoint(option, q[option].EndTime, q[option].Close),
                        new IndicatorDataPoint(mirror, q[mirror].EndTime, q[mirror].Close),
                        new IndicatorDataPoint(
                            option.Underlying, b[option.Underlying].EndTime, b[option.Underlying].Close
                        )
                    };
                    foreach (var dataPoint in dataPoints)
                    {
                        indicator.Update(dataPoint);
                    }

                    // Get the current value of the <?=$typeName?> indicator.
                    var value = indicator.Current.Value;
                }
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
    <pre class="python">class Manual<?=$typeName?>IndicatorAlgorithm(QCAlgorithm):

    def initialize(self) -&gt; None:
        self.set_start_date(2024, 1, 1)
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
        chain = self.option_chain(self._underlying, flatten=True).data_frame
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
            for contract_a, contract_b in [(contract1, contract2), (contract2, contract1)]:
                contract_a.<?=$typeName?> = <?=$typeName?>(
                        contract_a.symbol, self.risk_free_interest_rate_model, 
                        self._dividend_yield_provider, contract_b.symbol, self._option_pricing_model
                    )

            self._options = (call, put)

    def on_data(self, slice: Slice) -&gt; None:
        # Iterate through the indicators.
        for canonical, chain in slice.option_chain.items():
            for option in chain:
                indicator = self.securities[option].<?=$typeName?>
                mirror = Symbol.create_option(
                    option.underlying.value, option.id.market, option.id.option_style, 
                    OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT,
                    option.id.strike_price, option.id.date
                )<?=$mirrorExtensionPy?>

                # Check if price data is available for both contracts and the underlying asset.
                q = slice.quote_bars
                b = slice.bars
                if option in q and mirror in q and option.underlying in b:
                    data_points = [
                        IndicatorDataPoint(option, q[option].end_time, q[option].close),
                        IndicatorDataPoint(mirror, q[mirror].end_time, q[mirror].close),
                        IndicatorDataPoint(
                            option.underlying, b[option.underlying].end_time, b[option.underlying].close
                        )
                    ]
                    for data_point in data_points:
                        indicator.update(data_point)

                    # Get the current value of the <?=$typeName?> indicator.
                    value = indicator.current.value
        
        # Sell straddle as an example to trade.
        if not self.portfolio.invested:
            self.sell(self._options[0], 1)
            self.sell(self._options[1], 1)
        # Liquidate any assigned positions.
        if self.portfolio[self._underlying].invested:
            self.liquidate(self._underlying)</pre>
</div>

<p>For more information about the <code><?=$typeName?></code> constructor, see <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">Using <?=$helperMethod?> Indicator</a>.</p>
