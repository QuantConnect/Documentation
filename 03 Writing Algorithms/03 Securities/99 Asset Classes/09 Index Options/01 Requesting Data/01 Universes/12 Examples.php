<p>The following examples demonstrate some common practices for requesting Index Options universe data.</p>

<h4>Example 1: Selecting for 0DTE Contracts</h4>

<p><span class='new-term'>0DTE Options</span> are option contracts that expire on the same day they are traded. To create a universe with 0DTE options, call the <code class="csharp">SetFilter</code> or <code class="python">set_filter</code> method. In this example, we will select 0DTE contracts within 1 strike price level and a Delta between 0.25 and 0.75. Then, we will go long on the highest strike price call contract of the SPX index and hold until expiry.</p> 

<div class="section-example-container">
    <pre class="csharp">private Option _option;

public override void Initialize()
{
    SetStartDate(2023, 1, 1);
    SetEndDate(2024, 1, 1);
    SetCash(100000);

    // Subscribe to the option chain.
    _option = AddIndexOption("SPX", Resolution.Daily);

    // Filter the option universe to only select 0DTE options.
    _option.SetFilter(u => u.IncludeWeeklys().Expiration(0, 0).Strikes(-1, 1));

    // Filter the option universe by Delta.
    _option.SetFilter(optionFilterUniverse => optionFilterUniverse.Delta(0.25m, 0.75m));
}

public override void OnData(Slice slice)
{
    if (Portfolio.Invested)
    {
        return;
    }

    // Get the option chain data.
    if (!slice.OptionChains.TryGetValue(_option.Symbol, out var chain))
    {
        return;
    }

    // Select the call Option contract.
    var calls = chain.Where(contract => contract.Right == OptionRight.Call).ToList();
    if (calls.Count < 1) return;

    // Sorted the contracts according to their strike prices.
    calls = calls.OrderBy(x => x.Strike).ToList();

    // Buy 1 0DTE call option contract for the SPX index.
    Buy(calls[0].Symbol, 1);
}</pre>
    <pre class="python">def initialize(self) -&gt None:
    self.set_start_date(2023,1,1)
    self.set_end_date(2024,1,1)
    self.set_cash(100_000)
    # Subscribe to the option chain.
    self._option = self.add_index_option("SPX", Resolution.DAILY)
    # Filter the option universe to only select 0DTE options.
    self._option.set_filter(lambda u: u.include_weeklys().expiration(0, 0).strikes(-1, 1))
    # Filter the option universe by Delta.
    self._option.set_filter(lambda option_filter_universe: option_filter_universe.delta(0.25, 0.75))

def on_data(self, slice: Slice) -&gt None:
    if self.portfolio.invested:
        return
    # Get the option chain data.
    chain = slice.option_chains.get(self._option.symbol)
    if not chain:
        return
    # Select the call Option contract.
    calls = [contract for contract in chain if contract.right == OptionRight.CALL]
    if len(calls) < 1: return
    # Sorted the contracts according to their strike prices.
    calls = sorted(calls, key=lambda x: x.Strike)
    # Buy 1 0DTE call option contract for the SPX index.
    self.Buy(calls[0].Symbol, 1)</pre>
</div>
