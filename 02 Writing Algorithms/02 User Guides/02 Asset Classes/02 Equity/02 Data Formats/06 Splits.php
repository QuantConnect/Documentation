- What are splits? Increase or reduction of the number of shares to change the stock's liquidity.<br>
- Splits have a type of Warning and SplitOccurred<br>
- In backtesting, they are streamed to your algorithm in a Slice object that is emitted at midnight<br>

Splits are handled by the engine depeding on the data normalization mode or whether the algorithm is running in live mode<br>
- Raw data and live mode:<br>
   - It's information is used by the engine to adjust the quantity of the positions accordingly ("SplitOccurred")<br>
   - If the quantity is not a valid lot size, the remaining value is credited to your account currency.<br>
- Other mode with backtesting:<br>
   - The splts are factored into the price and volume.<br>

Only applied to US Equity. Splits close all options positions.