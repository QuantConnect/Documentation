def SpecificTime(self):
    self.Log("SpecificTime: Fired at : {0}".format(self.Time))

def EveryDayAfterMarketOpen(self):
    self.Log("EveryDay.SPY 10 min after open: Fired at: {0}".format(self.Time))

def EveryDayAfterMarketClose(self):
    self.Log("EveryDay.SPY 10 min before close: Fired at: {0}".format(self.Time))

def EveryMonFriAtNoon(self):
    self.Log("Mon/Fri at 12pm: Fired at: {0}".format(self.Time))

def LiquidateUnrealizedLosses(self):
    ''' if we have over 1000 dollars in unrealized losses, liquidate'''
    if self.Portfolio.TotalUnrealizedProfit < -1000:
        self.Log("Liquidated due to unrealized losses at: {0}".format(self.Time))
        self.Liquidate()

def RebalancingCode(self):
    ''' Good spot for rebalancing code?'''
    pass