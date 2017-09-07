//Access data in a Slice object: 
// 1. Strong Typed Properties: Ticks, TradeBars, Delistings, SymbolChangedEvents, Splits and Dividends
var bars = slice.Bars; // e.g. bars["IBM"].Open

// 2. Strong Typed Get Accessor:
var bars = slice.Get<TradeBars>(); //e.g. bars["IBM"].Open
var bar   = slice.Get<TradeBar>("IBM"); //e.g. bar.Open

// 3. Dynamic String / Symbol Indexer:
var bar = slice["IBM"]; // e.g. bar.Open