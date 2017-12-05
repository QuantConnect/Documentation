# In Initialize
future = self.AddFuture(Futures.Indices.SP500EMini, Resolution.Minute)
future.SetFilter(timedelta(0), timedelta(182))
# or Lambda
future.SetFilter(universe => universe.Expiration(timedelta(0), timedelta(182)))