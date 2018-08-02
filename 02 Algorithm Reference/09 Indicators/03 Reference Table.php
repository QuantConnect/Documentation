<?php

/**
 * @var $getParameterList = function( array $parameters ) : string;
 */

$indicators = file_get_contents('https://cdn.quantconnect.com/docs/indicators.json');
$indicators = json_decode($indicators, true);

?>
<table id="table-indicators-reference" class="table qc-table table-striped data-table">
    <thead>
    <tr>
        <th>Indicators</th>
        <th>Usage</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($indicators['indicators'] as $indicator) {
        if ($indicator['prefix'] != "") {
            continue;
        }
        ?>
        <tr>
            <td width="30%">$[<?= $indicator['class'] ?>]</td>
            <td data-toggle="tooltip" title="<?= $indicator['summary'] ?>">
                <span class="indicators-method-usage">
                    var <?= strtolower($indicator['name']) ?>
                    = <?= ($indicator['prefix'] . $indicator['name'] . $getParameterList($indicator['parameters'])) ?>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<table id="table-indicators-patterns" class="table qc-table table-striped data-table">
    <thead>
    <tr>
        <th>Candlesticks Patterns</th>
        <th>Usage</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($indicators['indicators'] as $indicator) {
        if ($indicator['prefix'] != "CandlestickPatterns.") {
            continue;
        } ?>
        <tr>
            <td width="30%">$[<?= $indicator['class'] ?>]</td>
            <td data-toggle="tooltip" title="<?= $indicator['summary'] ?>">
                <span class="indicators-method-usage">
                    var <?= strtolower($indicator['name']) ?>
                    = <?= ($indicator['prefix'] . $indicator['name'] . $getParameterList($indicator['parameters'])) ?>
                </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<p></p>

<div class="section-example-container">
<pre class="csharp">
// 1. Using basic indicator at the same resolution as source security:
// TIP -> You can use string "IBM" or the security.Symbol object
var ema = EMA("IBM", 14);
var rsi = RSI("IBM", 14);

//2. Using indicator at different (higher) resolution to the source security:
var emaDaily = EMA("IBM", 14, Resolution.Daily);

//3. Indicator of a different property (default is close of bar/data):
// TIP -> You can use helper methods Field.Open, Field.High etc on the indicator selector:
var emaDailyHigh = EMA("IBM", 14, Resolution.Daily, point => ((TradeBar) point).High);

//4. Using the indicators:
//4.1  Setup in initialize:
_emaFast = EMA("IBM", 14);
_emaSlow = EMA("IBM", 28);

//4.2 Use in OnData:
if (_emaSlow.IsReady && _emaFast.IsReady) {
   if (_emaFast > _emaSlow) {
       //Long.
   } else if (_emaFast < _emaSlow) {
       //Short.
   } 
}

//NOTE. Some indicators require tradebars (ATR, AROON) so your selector must return a TradeBar object for those indicators.
</pre>
    <pre class="python">
# 1. Using basic indicator at the same resolution as source security:
self.ema = self.EMA("IBM", 14)
self.rsi = self.RSI("IBM", 14)

#2. Using indicator at different (higher) resolution to the source security:
self.emaDaily = self.EMA("IBM", 14, Resolution.Daily)

#3. Indicator of a different property (default is close of bar/data):
self.emaDailyHigh = self.EMA("IBM", 14, Resolution.Daily, Field.High)


#4. Using the indicators:
#4.1  Setup in initialize: make sure you've asked for the data for the asset.
self.AddEquity("IBM")
self.emaFast = self.EMA("IBM", 14);
self.emaSlow = self.EMA("IBM", 28);

#4.2 Consume the indicators in OnData.
if self.emaSlow.IsReady and self.emaFast.IsReady:
    if self.emaFast.Current.Value > self.emaSlow.Current.Value:
        self.Debug("Long")
    elif self.emaFast.Current.Value < self.emaSlow.Current.Value:
        self.Debug("Short")
</pre>
</div>