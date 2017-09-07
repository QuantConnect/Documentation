#Plot array of indicator objects; extending "Indicator" type.
self.Plot("Indicators", sma, rsi); 

#Plot array of indicator objects; extending "TradeBarIndicator" type.
self.Plot("Indicators", atr, aroon); 

#Currently, there is a limit of 4 indicators for each Plot call
#For complex plotting it might be easiest to simply plot your indicators individually.