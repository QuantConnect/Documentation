<p>To obtain the historical data of the <code><?=$typeName?></code>, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. Like ordinary historical data requests, the indicator history takes arguments of symbol(s), time period, <i>(optional)</i> resolution, and <i>(optional)</i> a selector function, with an extra argument of the indicator instance itself. It returns a <code class="csharp">DataDictionary&lt;IndicatorDataPoints&gt;</code><code class="python">pandas.DataFrame</code> object containing the historical data of the indicator.</p>

<p>The time period argument can be expressed as:</p>
<ul>
    <li>an <code>int</code> of the number of indicator data points requesting</li>
    <li>a <code class="csharp">TimeSpan</code><code class="python">timedelta</code> object as the time period needed from the current time</li>
    <li>two <code class="csharp">DateTime</code><code class="python">datetime</code> objects as the start and end time of the indicator history needed</li>
</ul>
<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
<? if($hasReference) { ?>
    private Symbol _reference;
<?} else if($isOptionIndicator) { ?>
    private Symbol _option, _mirrorOption;
<?}?>

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
<? if($hasReference) { ?>
        _reference = AddEquity("QQQ", Resolution.Daily).Symbol;
<?} else if($isOptionIndicator) { ?>
        _option = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 225m, new DateTime(2024, 7, 12));
        AddOptionContract(_option, Resolution.Daily);
        _mirrorOption = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 225m, new DateTime(2024, 7, 12));
        AddOptionContract(_mirrorOption, Resolution.Daily);
<?}?>
        var <?=strtolower($helperName)?> = <?=$helperPrefix?><?=$helperName?>(<?=str_replace("symbol", "_symbol", str_replace("option_mirror_symbol", "_mirrorOption", str_replace("option_symbol", "_option", $helperArguments)))?>);
<? if($hasReference) { ?>
        var multipleSymbolsCountIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, 100, Resolution.Minute);
        var multipleSymbolsTimeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, TimeSpan.FromDays(10), Resolution.Minute);
        var multipleSymbolsTimePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } else if($isOptionIndicator) { ?>
        var optionCountIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, 100, Resolution.Minute);
        var optionSymbolsTimeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, TimeSpan.FromDays(10), Resolution.Minute);
        var optionSymbolsTimePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } else { ?>
        var singleSymbolCountIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, 100, Resolution.Minute);
        var singleSymbolTimeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var singleSymbolTimePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } ?>
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
<? if($hasReference) { ?>
        self.reference = self.add_equity("QQQ", Resolution.DAILY).symbol
<?} else if($isOptionIndicator) { ?>
        self.option = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 225, datetime(2024, 7, 12))
        self.add_option_contract(self.option, Resolution.DAILY)
        self.mirror_option = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 225, datetime(2024, 7, 12))
        self.add_option_contract(self.mirror_option, Resolution.DAILY)
<?}?>
        <?=strtolower($helperName)?> = self.<?=$helperPrefix?><?=strtolower($helperName)?>(<?=str_replace("symbol", "self._symbol", str_replace("option_mirror_symbol", "self.mirror_option", str_replace("option_symbol", "self.option", $helperArguments)))?>)
<? if($hasReference) { ?>
        multiple_symbols_count_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.reference], 100, Resolution.MINUTE)
        multiple_symbols_timedelta_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.reference], timedelta(days=10), Resolution.MINUTE)
        multiple_symbols_time_period_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.reference], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } else if($isOptionIndicator) { ?>
        option_count_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.option, self.mirror_option], 100, Resolution.MINUTE)
        option_timedelta_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.option, self.mirror_option], timedelta(days=10), Resolution.MINUTE)
        option_time_period_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, [self._symbol, self.option, self.mirror_option], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } else { ?>
        single_symbol_count_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, self._symbol, 100, Resolution.MINUTE)
        single_symbol_timedelta_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, self._symbol, timedelta(days=10), Resolution.MINUTE)
        single_symbol_time_period_indicator_history = self.indicator_history(<?=strtolower($helperName)?>, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } ?></pre>
</div>

<p class='csharp'>You may also provide the historical data instead of the time argument to generate indicator history with respect to the time and values of the historical data provided.</p>
<div class="section-example-container">
    <pre class="csharp"><? if($hasReference) { ?>
var history = History(new[] { _symbol, _reference }, 100, Resolution.Minute);
<? } else if($isOptionIndicator) { ?>
var history = History(new[] { _symbol, _option, _mirrorOption }, 100, Resolution.Minute);
<? } else { ?>
var history = History(_symbol, 100, Resolution.Minute);
<? } ?>
var historyIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, history);</pre>
</div>

<p>The default indicator historical data will be calculated using the <code>Value</code> property of each <code>BaseData</code> object iterated. You can assign custom calculation on the value being processed through a <code>selector</code> function argument.</p>
<div class="section-example-container">
    <pre class="csharp">var indicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, 100, Resolution.Minute, (bar) =&gt; ((TradeBar)bar).High);</pre>
    <pre class="python">indicator_history = self.indicator_history(<?=strtolower($helperName)?>, 100, Resolution.Minute, lambda bar: bar.High)</pre>
</div>

<? if ($csharpProperties) { ?>
<p>To access the different properties of the returned indicator history, call the property directly of each <code>IndicatorDataPoint</code> entry.</p>
<div class="section-example-container">
    <pre class="csharp"><? foreach ($csharpProperties as $property) { ?>
var <?=strtolower($property)?> = indicatorHistory.Select(x => ((dynamic)x).<?=$property?>).ToList();
<? } ?>

// Alternative way
<? foreach ($csharpProperties as $property) { ?>
// var <?=strtolower($property)?> = indicatorHistory.Select(x => x["<?=strtolower($property)?>"]).ToList();
<? } ?></pre>
    <pre class="python"><? foreach ($pythonProperties as $property) { ?>
<?=strtolower($property)?> = indicator_history["<?=$property?>"]
<? } ?>

# Alternative way
<? foreach ($pythonProperties as $property) { ?>
# <?=strtolower($property)?> = indicator_history.<?=$property?>
<? } ?></pre>
</div>
<? } ?>
