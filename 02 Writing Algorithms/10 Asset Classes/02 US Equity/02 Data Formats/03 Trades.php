<div>TradeBars</div><div>-Definition: individual trades from the exchanges consolidated into price bars<br></div><div>- Add image from Handling Data docs<br>- 
Daily/hour/minute bars are adjusted to open (close) with with the 
official auction open (close) price. If there is no official auction 
price received before we must emit the bar, we use the first/last trade</div><div>-If there is little trading, or you are in the same time loop as when 
	you added the security, the time-slice may not have any data. <br></div><div>-Prices from tradebars are used fill trades in backtests (hour, daily resolutions)</div><div>&nbsp;&nbsp;&nbsp; - Opening &amp; closing prices are updated to the official opening/closing auction prices,&nbsp; used to fill MOO and MOC orders<br></div>


<div data-tree="QuantConnect.Data.Market.TradeBar"></div>

<br>
-Add snippet of accessing TradeBars in OnData