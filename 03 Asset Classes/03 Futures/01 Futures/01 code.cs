// In Initialize
var future = AddFuture(Futures.Indices.SP500EMini, Resolution.Minute);
future.SetFilter(TimeSpan.Zero, TimeSpan.FromDays(182));
// or Linq
future.SetFilter(universe => universe.Expiration(TimeSpan.Zero, TimeSpan.FromDays(182)));