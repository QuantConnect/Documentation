// In Initialize
var future = AddFuture(Futures.Indices.SP500EMini, Resolution.Minute);
future.SetFilter(TimeSpan.Zero, TimeSpan.FromDays(182));