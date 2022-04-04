Splits are handled by the engine depeding on the data normalization mode or whether the algorithm is running in live mode<br>
- Raw data and live mode:<br>
   - It's information is used by the engine to adjust the quantity of the positions accordingly ("SplitOccurred")<br>
   - If the quantity is not a valid lot size, the remaining value is credited to your account currency.<br>
- Other mode with backtesting:<br>
   - The splts are factored into the price and volume.<br>

Only applied to US Equity. Splits close all options positions. <br><br>

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

## TODO: <br>
- Add SplitType enum https://github.com/QuantConnect/Lean/blob/master/Common/Global.cs#L422-L433
<br>- May not arrive at the same resolution as the daily bars(? -- need to test) (likely the same for dividends, symbolchangedevents, and delistings)
<br>-ToString()