<?php

/**
 * @var $getParameterList = function( array $parameters ) : string;
 */

$indicators = file_get_contents('https://cdn.quantconnect.com/docs/indicators.json');
$indicators = json_decode($indicators, true);

?>
<style>
    #table-indicators-patterns .dummy-reference,
    #table-indicators-reference .dummy-reference {
        position: relative;
        top: -200px;
        display: block;
    }
</style>
<table id="table-indicators-reference" class="table qc-table table-striped">
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
            <td style="width:30%"><?php

                $htmlId = 'indicator-' . strtolower($indicator['class']);

                printf('<span class="dummy-reference" id="%s"></span>', $htmlId);
                printf("\$[%s,T:QuantConnect.Indicators.%s]", $indicator['class'], $indicator['class']);

                ?></td>
            <td style="text-align: left">
                <p><?= trim($indicator['summary']) ?></p>
                <pre class='prettyprint' style='padding: 15px 5px;border: none !important; background: transparent; font-size: 1em;'><?php

                    printf("var %s = %s%s%s",
                        strtolower($indicator['name']),
                        $indicator['prefix'],
                        $indicator['name'],
                        $getParameterList($indicator['parameters'])
                    );

                    ?></pre>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<table id="table-indicators-patterns" class="table qc-table table-striped">
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
            <td style="width: 30%"><?php

                $htmlId = 'indicator-candlesticks-pattern-' . strtolower($indicator['class']);

                printf('<span class="dummy-reference" id="%s"></span>', $htmlId);
                printf("\$[%s]", $indicator['class']);
    
                ?></td>
            <td style="text-align: left">
                <p><?= trim($indicator['summary']) ?></p>
                <pre class='prettyprint' style='padding: 15px 5px;border: none !important; background: transparent; font-size: 1em;'><?php

                    printf("var %s = %s%s%s",
                        strtolower($indicator['name']),
                        $indicator['prefix'],
                        $indicator['name'],
                        $getParameterList($indicator['parameters'])
                    );

                    ?></pre>
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
