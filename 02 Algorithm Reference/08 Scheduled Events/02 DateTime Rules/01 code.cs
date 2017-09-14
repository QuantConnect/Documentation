// Schedule an event to fire at a specific date/time
Schedule.On(DateRules.On(2013, 10, 7), TimeRules.At(13, 0), () =>
{
	Log("SpecificTime: Fired at : " + Time);
});

// Schedule an event to fire every trading day for a security
// The time rule here tells it to fire 10 minutes after SPY's market open
Schedule.On(DateRules.EveryDay("SPY"), TimeRules.AfterMarketOpen("SPY", 10), () =>
{
	Log("EveryDay.SPY 10 min after open: Fired at: " + Time);
});

// Schedule an event to fire every trading day for a security
// The time rule here tells it to fire 10 minutes before SPY's market close
Schedule.On(DateRules.EveryDay("SPY"), TimeRules.BeforeMarketClose("SPY", 10), () =>
{
	Log("EveryDay.SPY 10 min before close: Fired at: " + Time);
});

// Schedule an event to fire on certain days of the week
Schedule.On(DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday), TimeRules.At(12, 0), () =>
{
	Log("Mon/Fri at 12pm: Fired at: " + Time);
});