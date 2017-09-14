// Create, initialize a consolidator and add an indicator
private SimpleMovingAverage _sma;

public override void Initialize()
{
	//...other initialization...
	AddEquity("SPY", Resolution.Minute);
	var consolidator = new TradeBarConsolidator(30);
	_sma = new SimpleMovingAverage(10);
	RegisterIndicator("SPY", _sma, consolidator);
	SubscriptionManager.AddConsolidator("SPY", consolidator);
}