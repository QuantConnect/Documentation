# In Initialize:
self.AddUniverse(self.CoarseSelectionFunction)

def CoarseSelectionFunction(self, coarse):
    '''Take the top 50 by dollar volume using coarse'''
    # sort descending by daily dollar volume
    sortedByDollarVolume = sorted(coarse, \
        key=lambda x: x.DollarVolume, reverse=True) 

    # we need to return only the symbol objects
    return [ x.Symbol for x in sortedByDollarVolume[:50] ]