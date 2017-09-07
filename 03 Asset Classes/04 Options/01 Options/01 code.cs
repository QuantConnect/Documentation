// In Initialize
var option = AddOption("GOOG");
option.SetFilter(-2, 2, TimeSpan.Zero, TimeSpan.FromDays(182));
// or Linq
option.SetFilter(universe => from symbol in universe
                                .WeeklysOnly()
                                .Expiration(TimeSpan.Zero, TimeSpan.FromDays(10))
                                    where symbol.ID.OptionRight != OptionRight.Put &&
                                    universe.Underlying.Price - symbol.ID.StrikePrice < 60
                                    select symbol);