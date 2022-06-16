<p><code>TradeBar</code> objects are individual trades from the exchanges that LEAN consolidates into price bars.</p>

<img src="https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png" class="img-responsive">

<p><code>TradeBar</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.TradeBar"></div>

<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, </p>


Daily/hour/minute bars are adjusted to open (close) with with the 
official auction open (close) price. If there is no official auction 
price received before we must emit the bar, we use the first/last trade</div><div>-If there is little trading, or you are in the same time loop as when 
	you added the security, the time-slice may not have any data. <br></div><div>-Prices from tradebars are used fill trades in backtests (hour, daily resolutions)</div><div>&nbsp;&nbsp;&nbsp; - Opening &amp; closing prices are updated to the official opening/closing auction prices,&nbsp; used to fill MOO and MOC orders<br></div>


<br>
-Add snippet of accessing TradeBars in OnData
<br>-The examples on this page should check if the slice contains the data before indexing it. (https://github.com/QuantConnect/Documentation/issues/204).