<p>To create a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/manual-indicators'>manual indicator</a> for <?=$name?>, call the <code><?=$typeName?></code> constructor.</p>

<div class="section-example-container">
    <pre class="csharp">private <?=$typeName?> _<?=strtolower($typeName)?>;

public override void Initialize()
{
    var equity = AddEquity("AAPL").Symbol;
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    var interestRateProvider = new InterestRateProvider();
    var dividendYieldProvider = new DividendYieldProvider(equity);

    // Example of using the single-contract IV calculation:
    _<?=strtolower($typeName)?> = new <?=$typeName?>(option, interestRateProvider, dividendYieldProvider);

    // Example of using the using mirror-contract IV calculation:
    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);
    _<?=strtolower($typeName)?> = new <?=$typeName?>(option, interestRateProvider, dividendYieldProvider, mirrorOption);
}</pre>
    <pre class="python">def Initialize(self):
    equity = self.AddEquity("AAPL").Symbol
    option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505, datetime(2014, 6, 27))
    self.AddOptionContract(option)

    interest_rate_provider = InterestRateProvider()
    dividend_yield_provider = DividendYieldProvider(equity)

    # Example of using the single-contract IV calculation:
    self.<?=strtolower($typeName)?> = <?=$typeName?>(option, interest_rate_provider, dividend_yield_provider)

    # Example of using the using mirror-contract IV calculation:
    mirror_option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27))
    AddOptionContract(mirror_option)
    self.<?=strtolower($typeName)?> = <?=$typeName?>(option, interest_rate_provider, dividend_yield_provider, mirror_option)
</pre>
</div>

<p>For more information about the <code><?=$typeName?></code> constructor, see <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/<?=$indicatorPage?>">Using <?=$typeName?> Indicator</a>.</p>
