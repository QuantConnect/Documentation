<p>
    To get the historical data of the <code><?=$typeName?></code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. 
	This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data.
	Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time.
	If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>

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
        var countIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _reference }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } else if($isOptionIndicator) { ?>
        var countIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, new[] { _symbol, _option, _mirrorOption }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } else { ?>
        var countIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
<? } ?>
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
<? if($hasReference) { ?>
        self._reference = self.add_equity("QQQ", Resolution.DAILY).symbol
<?} else if($isOptionIndicator) { ?>
        self._option = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 225, datetime(2024, 7, 12))
        self.add_option_contract(self._option, Resolution.DAILY)
        self._mirror_option = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 225, datetime(2024, 7, 12))
        self.add_option_contract(self._mirror_option, Resolution.DAILY)
<?}?>
        <?=$pyHelperName?> = self.<?=$helperPrefix?><?=$pyHelperName?>(<?=str_replace("symbol", "self._symbol", str_replace("option_mirror_symbol", "self.mirror_option", str_replace("option_symbol", "self.option", $helperArguments)))?>)
<? if($hasReference) { ?>
        count_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._reference], 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._reference], timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._reference], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } else if($isOptionIndicator) { ?>
        count_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._option, self._mirror_option], 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._option, self._mirror_option], timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(<?=$pyHelperName?>, [self._symbol, self._option, self._mirror_option], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } else { ?>
        count_indicator_history = self.indicator_history(<?=$pyHelperName?>, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(<?=$pyHelperName?>, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(<?=$pyHelperName?>, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
<? } ?></pre>
</div>

<p>To make the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method update the indicator with an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators#07-Alternative-Price-Fields'>alternative price field</a> instead of the close (or mid-price) of each bar, pass a <code>selector</code> argument.</p>
<div class="section-example-container">
    <pre class="csharp">var indicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, 100, Resolution.Minute, (bar) =&gt; ((TradeBar)bar).High);</pre>
    <pre class="python">indicator_history = self.indicator_history(<?=$pyHelperName?>, 100, Resolution.MINUTE, lambda bar: bar.high)
indicator_history_df = indicator_history.data_frame</pre>
</div>

<p class='csharp'>If you already have a list of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects, you can pass them to the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method to avoid the internal history request.</p>
<div class="csharp section-example-container">
    <pre class="csharp"><? if($hasReference) { ?>
var history = History(new[] { _symbol, _reference }, 100, Resolution.Minute);
<? } else if($isOptionIndicator) { ?>
var history = History(new[] { _symbol, _option, _mirrorOption }, 100, Resolution.Minute);
<? } else { ?>
var history = History(_symbol, 100, Resolution.Minute);
<? } ?>
var historyIndicatorHistory = IndicatorHistory(<?=strtolower($helperName)?>, history);</pre>
</div>

<? if ($csharpProperties) { ?>
<p>To access the properties of the indicator history, <span class='csharp'>invoke the property of each <code>IndicatorDataPoint</code> object.</span><span class='python'>index the DataFrame with the property name.</span></p>
<div class="section-example-container">
    <pre class="csharp"><? foreach ($csharpProperties as $property) { ?>
var <?=strtolower($property)?> = indicatorHistory.Select(x => ((dynamic)x).<?=$property?>).ToList();
<? } ?>

// Alternative way
<? foreach ($csharpProperties as $property) { ?>
// var <?=strtolower($property)?> = indicatorHistory.Select(x => x["<?=strtolower($property)?>"]).ToList();
<? } ?></pre>
    <pre class="python"><? foreach ($pythonProperties as $property) { ?>
<?=strtolower($property)?> = indicator_history_df["<?=$property?>"]
<? } ?>

# Alternative way
<? foreach ($pythonProperties as $property) { ?>
# <?=strtolower($property)?> = indicator_history_df.<?=$property?>

<? } ?></pre>
</div>
<? } ?>
