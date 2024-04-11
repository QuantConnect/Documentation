<p>Follow the below example to create a manual indicators for <code><?=$typeName?></code>.</p>

<div class="section-example-container">
    <pre class="csharp">private <?=$typeName?> _<?=strtolower($typeName)?>;

public override void Initialize()
{
    var equity = AddEquity("AAPL").Symbol;
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    var interestRateProvider = new InterestRateProvider();
    var dividendYieldProvider = new DividendYieldProvider(equity);

    // indicator using single-contract IV calculation
    _<?=strtolower($typeName)?> = new <?=$typeName?>(option, interestRateProvider, dividendYieldProvider);

    // indicator using mirror-contract IV calculation
    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);
    _<?=strtolower($typeName)?> = new <?=$typeName?>(option, interestRateProvider, dividendYieldProvider, mirrorOption);
}</pre>
    <pre class="python">def initialize(self):
    equity = self.add_equity("AAPL").symbol
    option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 505, datetime(2014, 6, 27))
    self.add_option_contract(option)

    interest_rate_provider = InterestRateProvider()
    dividend_yield_provider = DividendYieldProvider(equity)

    # indicator using single-contract IV calculation
    self.<?=strtolower($typeName)?> = <?=$typeName?>(option, interest_rate_provider, dividend_yield_provider)

    # indicator using mirror-contract IV calculation
    mirror_option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 505m, new DateTime(2014, 6, 27))
    AddOptionContract(mirror_option)
    self.<?=strtolower($typeName)?> = <?=$typeName?>(option, interest_rate_provider, dividend_yield_provider, mirror_option)
</pre>
</div>

<p>For details of the <code><?=$typeName?></code> indicator method, please refer to <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">its indicator page</a>.