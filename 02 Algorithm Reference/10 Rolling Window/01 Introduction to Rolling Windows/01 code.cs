// In Initialize, create the rolling windows
public override void Initialize()
{
    // Create a Rolling Window to keep the 4 decimal
    _closeWindow = new RollingWindow<decimal>(4);
    // Create a Rolling Window to keep the 2 TradeBar
    _tradeBarWindow = new RollingWindow<TradeBar>(2);
    // Create a Rolling Window to keep the 2 QuoteBar
    _quoteBarWindow = new RollingWindow<QuoteBar>(2);
}

// In OnData, update the rolling windows
 public override void OnData(Slice data)
{
    // Add SPY bar close in the rolling window
    _closeWindow.Add(data["SPY"].Close);
    // Add SPY TradeBar in rolling window
    _tradeBarWindow.Add(data["SPY"]);
    // Add EURUSD QuoteBar in rolling window
    _quoteBarWindow.Add(data["EURUSD"]);
}