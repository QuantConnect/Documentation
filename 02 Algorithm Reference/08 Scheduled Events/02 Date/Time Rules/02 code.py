# Schedule.On third argument type is System.Action or System.Action[System.String,System.DateTime]
# we need to cast the callback function using Action(...) to make it work

# schedule an event to fire at a specific date/time
self.Schedule.On(self.DateRules.On(2013, 10, 7), \ 
                 self.TimeRules.At(13, 0), \ 
                 Action(self.SpecificTime))

# schedule an event to fire every trading day for a security the
# time rule here tells it to fire 10 minutes after SPY's market open
self.Schedule.On(self.DateRules.EveryDay("SPY"), \ 
                 self.TimeRules.AfterMarketOpen(self.spy, 10), \        
                 Action(self.EveryDayAfterMarketOpen))

# schedule an event to fire every trading day for a security the
# time rule here tells it to fire 10 minutes before SPY's market close
self.Schedule.On(self.DateRules.EveryDay("SPY"), \
                 self.TimeRules.BeforeMarketClose("SPY", 10), \
                 Action(self.EveryDayAfterMarketClose))

# schedule an event to fire on certain days of the week
self.Schedule.On(self.DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday), \
                 self.TimeRules.At(12, 0), \
                 Action(self.EveryMonFriAtNoon))

# the scheduling methods return the ScheduledEvent object which can be used 
# for other things here I set the event up to check the portfolio value every
# 10 minutes, and liquidate if we have too many losses
self.Schedule.On(self.DateRules.EveryDay(), \ 
                 self.TimeRules.Every(timedelta(minutes=10)), \
                 Action(self.LiquidateUnrealizedLosses))

# schedule an event to fire at the beginning of the month, the symbol is
# optional. 
# if specified, it will fire the first trading day for that symbol of the month,
# if not specified it will fire on the first day of the month
self.Schedule.On(self.DateRules.MonthStart("SPY"), \
                 self.TimeRules.AfterMarketOpen("SPY"), \
                 Action(self.RebalancingCode))