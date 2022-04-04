<p>
    $[Split,T:QuantConnect.Data.Market.Split] events are triggered on a share split or reverse split event. It provides a SplitFactor and ReferencePrice.
</p>
<div class="section-example-container">
    <pre class="python">
def Initialize(self):
    self.SetStartDate(2003, 2, 1)
    self.SetEndDate(2003, 2, 28)
    self.SetCash(100000)
    self.msft = self.AddEquity("MSFT", Resolution.Daily)
    self.msft.SetDataNormalizationMode(DataNormalizationMode.Raw)

def OnData(self, data):
    if not self.Portfolio.Invested:
        self.Buy("MSFT", 100)
    
    ## If MSFT had a split, print out information about it
    if data.Splits.ContainsKey("MSFT"):
        ## Log split information
        spySplit = data.Splits['MSFT']
        if spySplit.Type == 0:
            self.Log('MSFT stock will split next trading day')
        if spySplit.Type == 1:
            self.Log("Split type: {0}, Split factor: {1}, Reference price: {2}".format(spySplit.Type, spySplit.SplitFactor, spySplit.ReferencePrice))
</pre>
</div>

<div>- What are splits? Increase or reduction of the number of shares to change the stock's liquidity.</div><div>- In backtesting, they are streamed to your algorithm in a Slice object that is emitted at midnight</div><div>- Splits are handled by the engine depeding on the data normalization mode or whether the algorithm is running in live mode<br>&nbsp;&nbsp; - Raw data and live mode:<br>&nbsp;&nbsp; - It's information is used by the engine to adjust the quantity of the positions accordingly ("SplitOccurred")<br>&nbsp;&nbsp; - If the quantity is not a valid lot size, the remaining value is credited to your account currency.<br>
- Other mode with backtesting:<br>&nbsp;&nbsp; - The splts are factored into the price and volume.<br>

Only applied to US Equity. Splits close all options positions. <br></div><div>-ToString()<br></div><div><br></div><div>-By default, data is split adjusted. When using Raw data, splits are applied directly to your portfolio quantity<br></div><div>-Include a high-level definition of what a split/reverse-split is(?)</div><div>-Split events are triggered on a share split or reverse split event</div><div>-Show properties of Split class</div><div>&nbsp;&nbsp;&nbsp; - SplitFactor and ReferencePrice</div><div>&nbsp;&nbsp;&nbsp; - Type<br></div><div>-Show values for SplitType enum</div><div>&nbsp;&nbsp;&nbsp;&nbsp; - Warning</div><div>&nbsp;&nbsp;&nbsp;&nbsp; - SplitOccurred</div><div>-Access from the time-slice<br></div>-Full example: DividendAlgorithm.Py<div>-Data saved in factor files<br></div><div></div>


<div class="section-example-container">
        <pre class="csharp">if (data.Splits.ContainsKey(_msft))
{
    var split = data.Splits[_msft];
}</pre>
        <pre class="python">if self.msft in data.Splits:
    split = data.Splits[self.msft]</pre>
    </div>