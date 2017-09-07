// Inside your initialize function:

// Subscriptions added via universe selection will have this resolution
UniverseSettings.Resolution = Resolution.Hour;

// Force securities to remain in the universe for a minimum of 30 days
UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(30);

// Helper: Add US-equity universe for the top 50 stocks by dollar volume
AddUniverse(Universe.DollarVolume.Top(50));

// Helper: Add US-equity universe for the bottom 50 stocks by dollar volume
AddUniverse(Universe.DollarVolume.Bottom(50));

// Helper: Add US-equity universe for the 90th dollar volume percentile
AddUniverse(Universe.DollarVolume.Percentile(90));

// Helper: Add US-equity universe for stocks between the 70th and 80th dollar volume percentile
AddUniverse(Universe.DollarVolume.Percentile(70, 80));