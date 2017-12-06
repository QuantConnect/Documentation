# In Initialize
option = self.AddOption("GOOG");
option.SetFilter(-2, 2, timedelta(0), timedelta(182))
# or Lambda
option.SetFilter(lambda universe: universe.IncludeWeeklys().Strikes(-2, +2).Expiration(timedelta(0), timedelta(182)))