# In Initialize
self.AddUniverse(NyseTopGainers, "myCustomUniverse", Resolution.Daily, self.nyseTopGainers)

def nyseTopGainers(self, data):
    return [ x.Symbol for x in data if x["Rank"] > 5 ]