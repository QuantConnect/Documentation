-The Future returned by the AddFuture method is the continuous future. 
<br>-It may be helpful to apply your indicators to the continuous future time series
<br>-You can configure the continuous contract settings by passing arguments to the AddFuture method
<br>-When the contract rolls over, you receive a SymbolChangedEvent in OnData
<br>&nbsp;&nbsp;&nbsp;-Example

<h4>Price Scaling</h4>
(dataMappingMode)
<br>-ForwardPanamaCanal: Eliminates price jumps between two consecutive contracts, adding a factor based on the difference of their prices. First contract is the true one, factor 0
<br>-BackwardsPanamaCanal: Eliminates price jumps between two consecutive contracts, adding a factor based on the difference of their prices. Last contract is the true one, factor 0
<br>-BackwardsRatio: Eliminates price jumps between two consecutive contracts, multiplying the prices by their ratio. Last contract is the true one, factor 1.
<br>-Raw: No price adjustment is made.

<h4>Contract Rollover Logic</h4>
(dataNormalizationMode)
<br>-LastTradingDay: The contract maps on the previous day of expiration of the front month.
<br>-FirstDayMonth: The contract maps on the first date of the delivery month of the front month. If the contract expires prior to this date, then it rolls on the contract's last trading date instead.
<br>-OpenInterest: The contract maps when the back month contract has a higher traded volume that the current front month.

<h4>Front and Back Months</h4>
(contractDepthOffset)
<br>- From the `AddFuture` API `contractDepthOffset` will allow specifying which contract to use, 0 (default) is the front month, 1 the following back month and so on. Initially we have added support for the first 2 back months contracts.
