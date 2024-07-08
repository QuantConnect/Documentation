<p>To obtain the historical data of the <code><?=$typeName?></code>, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. Like ordinary historical data requests, the indicator history takes arguments of symbol(s), time period, <i>(optional)</i> resolution, and <i>(optional)</i> a selector function, with an extra argument of the indicator instance itself. It returns a <code class="csharp">DataDictionary&lt;IndicatorDataPoints&gt;</code><code class="python">pandas.DataFrame</code> object containing the historical data of the indicator.</p>

<p>The time period argument can be expressed as:</p>
<ul>
    <li>an <code>int</code> of the number of indicator data points requesting</li>
    <li>a <code class="csharp">TimeSpan</code><code class="python">timedelta</code> object as the time period needed from the current time</li>
    <li>two <code class="csharp">DateTime</code><code class="python">datetime</code> objects as the start and end time of the indicator history needed</li>
</ul>
<div class="section-example-container">
    <pre class="csharp">var singleSymbolCountIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, _symbol, 100, Resolution.Minute);
var singleSymbolTimeSpanIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
var singleSymbolTimePeriodIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, _symbol, new DateTime(2020, 1, 1), new DateTime(2021, 1, 1), Resolution.Minute);

var multipleSymbolsCountIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, new[] { _symbol, _symbol2 }, 100, Resolution.Minute);
var multipleSymbolsTimeSpanIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, new[] { _symbol, _symbol2 }, TimeSpan.FromDays(10), Resolution.Minute);
var multipleSymbolsTimePeriodIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, new[] { _symbol, _symbol2 }, new DateTime(2020, 1, 1), new DateTime(2021, 1, 1), Resolution.Minute);</pre>
    <pre class="python">single_symbol_count_indicator_history = self.indicator_history(<?=$indicatorVariable?>, self._symbol, 100, Resolution.MINUTE)
single_symbol_timedelta_indicator_history = self.indicator_history(<?=$indicatorVariable?>, self._symbol, timedelta(days=10), Resolution.MINUTE)
single_symbol_time_period_indicator_history = self.indicator_history(<?=$indicatorVariable?>, self._symbol, datetime(2020, 1, 1), datetime(2021, 1, 1), Resolution.MINUTE)

multiple_symbols_count_indicator_history = self.indicator_history(<?=$indicatorVariable?>, [self._symbol, self._symbol2], 100, Resolution.MINUTE)
multiple_symbols_timedelta_indicator_history = self.indicator_history(<?=$indicatorVariable?>, [self._symbol, self._symbol2], timedelta(days=10), Resolution.MINUTE)
multiple_symbols_time_period_indicator_history = self.indicator_history(<?=$indicatorVariable?>, [self._symbol, self._symbol2], datetime(2020, 1, 1), datetime(2021, 1, 1), Resolution.MINUTE)</pre>
</div>

<p>You may also provide the history data instead of the time argument to generate indicator history with respect to the historical time and values provided.</p>
<div class="section-example-container">
    <pre class="csharp">var history = History(_symbol, 100, Resolution.Minute);
var historyIndicatorHistory = IndicatorHistory(<?=$indicatorVariable?>, _symbol, history, Resolution.Daily);</pre>
    <pre class="python">history = self.history(self._symbol, 100, Resolution.MINUTE)
history_indicator_history = self.indicator_history(<?=$indicatorVariable?>, self._symbol, history, Resolution.DAILY)</pre>
</div>

<? if ($isMultipleType) { ?>
<p>You can also specify the return type of each entries of the indicator history request.</p>
<div class="section-example-container">
    <pre class="csharp">var otherTypeIndicatorHistory = IndicatorHistory&lt;T&gt;(<?=$indicatorVariable?>, _symbol, 100, Resolution.Daily);</pre>
    <pre class="python">other_type_indicator_history = self.indicator_history(T, <?=$indicatorVariable?>, self._symbol, 100, Resolution.DAILY)</pre>
</div>
<p>where <code>T</code> can be different <a href="/docs/v2/writing-algorithms/indicators/key-concepts#03-Indicator-Types">indicator types</a> supported by the <code><?=$typeName?></code> indicator.</p>
<? } ?>

<p>To access the different properties of the returned indicator history, call the property directly of each <code>IndicatorDataPoint</code> entry.</p>
<div class="section-example-container">
    <pre class="csharp"><? foreach ($csharpProperties as $property) { ?>
var <?=strtolower($property)?> = singleSymbolCountIndicatorHistory.Select(x => x.<?=$property?>).ToList();
<? } ?>
<? foreach ($csharpProperties as $property) { ?>
var <?=strtolower($property)?> = multipleSymbolsCountIndicatorHistory.Select(x => x[_symbol].<?=$property?>).ToList();
<? } ?></pre>
    <pre class="python"><? foreach ($pythonProperties as $property) { ?>
<?=strtolower($property)?> = single_symbol_count_indicator_history["<?=$property?>"]
<? } ?>
<? foreach ($pythonProperties as $property) { ?>
<?=strtolower($property)?> = multiple_symbols_count_indicator_history.loc[_symbol]["<?=$property?>"]
<? } ?></pre>
</div>