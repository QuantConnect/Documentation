<p>
 The following examples demonstrate some common practices for requesting Index Options universe data.
</p>
<h4>
 Example 1: Selecting for 0DTE Contracts
</h4>
<p>
 <span class="new-term">
  0DTE Options
 </span>
 are option contracts that expire on the same day they are traded. To create a universe with 0DTE options, call the
 <code class="csharp">
  SetFilter
 </code>
 or
 <code class="python">
  set_filter
 </code>
 method. In this example, we will select 0DTE contracts within 1 strike price level and a Delta between 0.25 and 0.75. Then, we will go long on the highest strike price call contract of the SPX index and hold until expiry.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class BasicIndexOptionAlgorithm : QCAlgorithm
{
    private Option _option;

    public override void Initialize()
    {
        SetStartDate(2023, 1, 1);
        SetEndDate(2024, 1, 1);
        SetCash(100000);
        // Subscribe to the option chain.
        _option = AddIndexOption("SPX", "SPXW");
        // Filter the option universe to only select 0DTE options.
        _option.SetFilter(u =&gt; u.IncludeWeeklys().Expiration(0, 0).Strikes(-1, 1));
        // Filter the option universe by Delta. The last SetFilter call prevails.
        // _option.SetFilter(optionFilterUniverse =&gt; optionFilterUniverse.Delta(0.25m, 0.75m));
    }

    public override void OnData(Slice slice)
    {
        // Get the option chain data.
        if (Portfolio.Invested || !slice.OptionChains.TryGetValue(_option.Symbol, out var chain))
        {
            return;
        }
        // Sorted the call Option contracts according to their strike prices.
        var call = chain.Where(contract =&gt; contract.Right == OptionRight.Call).OrderBy(x =&gt; x.Strike).FirstOrDefault();
        if (call == null) return;

        // Buy 1 0DTE call option contract for the SPX index.
        Buy(call.Symbol, 1);
    }
}</pre>
 <script class="csharp-result" type="text">
  {
    "Total Orders": "499",
    "Average Win": "1.83%",
    "Average Loss": "-1.06%",
    "Compounding Annual Return": "9.577%",
    "Drawdown": "25.500%",
    "Expectancy": "0.307",
    "Start Equity": "100000",
    "End Equity": "109523.5",
    "Net Profit": "9.524%",
    "Sharpe Ratio": "0.182",
    "Sortino Ratio": "0.325",
    "Probabilistic Sharpe Ratio": "22.676%",
    "Loss Rate": "52%",
    "Win Rate": "48%",
    "Profit-Loss Ratio": "1.73",
    "Alpha": "-0.166",
    "Beta": "1.606",
    "Annual Standard Deviation": "0.238",
    "Annual Variance": "0.056",
    "Information Ratio": "-0.499",
    "Tracking Error": "0.174",
    "Treynor Ratio": "0.027",
    "Total Fees": "$0.00",
    "Estimated Strategy Capacity": "$470000.00",
    "Lowest Capacity Asset": "SPXW YEHL1QA9FPM6|SPX 31",
    "Portfolio Turnover": "1.07%",
    "OrderListHash": "86121c66035b3fca26286b90e7da8986"
}
 </script>
 <pre class="python">class BasicIndexOptionAlgorithm(QCAlgorithm):

    def initialize(self) -&gt; None:
        self.set_start_date(2023,1,1)
        self.set_end_date(2024,1,1)
        self.set_cash(100_000)
        # Subscribe to the option chain.
        self._option = self.add_index_option("SPX", "SPXW")
        # Filter the option universe to only select 0DTE options.
        self._option.set_filter(lambda u: u.include_weeklys().expiration(0, 0).strikes(-1, 1))
        # Filter the option universe by Delta. The last set_filter call prevails.
        # self._option.set_filter(lambda option_filter_universe: option_filter_universe.delta(0.25, 0.75))

    def on_data(self, slice: Slice) -&gt; None:
        if self.portfolio.invested:
            return
        # Get the option chain data.
        chain = slice.option_chains.get(self._option.symbol)
        if not chain:
            return
        # Sorted the call Option contracts according to their strike prices.
        calls = sorted([contract for contract in chain if contract.right == OptionRight.CALL], key=lambda x: x.strike)
        if not calls:
            return
        # Buy 1 0DTE call option contract for the SPX index.
        self.Buy(calls[0].symbol, 1)</pre>
 <script class="python-result" type="text">
  {
    "Total Orders": "499",
    "Average Win": "1.83%",
    "Average Loss": "-1.06%",
    "Compounding Annual Return": "9.577%",
    "Drawdown": "25.500%",
    "Expectancy": "0.307",
    "Start Equity": "100000",
    "End Equity": "109523.5",
    "Net Profit": "9.524%",
    "Sharpe Ratio": "0.182",
    "Sortino Ratio": "0.325",
    "Probabilistic Sharpe Ratio": "22.676%",
    "Loss Rate": "52%",
    "Win Rate": "48%",
    "Profit-Loss Ratio": "1.73",
    "Alpha": "-0.166",
    "Beta": "1.606",
    "Annual Standard Deviation": "0.238",
    "Annual Variance": "0.056",
    "Information Ratio": "-0.499",
    "Tracking Error": "0.174",
    "Treynor Ratio": "0.027",
    "Total Fees": "$0.00",
    "Estimated Strategy Capacity": "$470000.00",
    "Lowest Capacity Asset": "SPXW YEHL1QA9FPM6|SPX 31",
    "Portfolio Turnover": "1.07%",
    "OrderListHash": "674f741252d2a8a75e25b6a0177627b7"
}
 </script>
</div>
