// Create and initialize a consolidator
public override void Initialize()
{
	//...other initialization...
	AddEquity("SPY", Resolution.Minute);
	var consolidator = new TradeBarConsolidator(15);
	consolidator.DataConsolidated += {
		Debug(Time.ToString() + " > New Bar!");
	};
	SubscriptionManager.AddConsolidator("SPY", consolidator);
}