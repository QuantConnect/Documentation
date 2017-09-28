# In Initialize:
self.AddUniverse(self.CoarseSelectionFunction, self.FineSelectionFunction)

def CoarseSelectionFunction(self, coarse):
    '''Take the top 50 by dollar volume using coarse'''
    # sort descending by daily dollar volume
    sortedByDollarVolume = sorted(coarse, \
        key=lambda x: x.DollarVolume, reverse=True) 

    # we need to return only the symbol objects
    return [ x.Symbol for x in sortedByDollarVolume[:50] ]

def FineSelectionFunction(self, fine):
    '''sort the data by P/E ratio and take the top 10 '''
    # sort descending by P/E ratio
    sortedByPeRatio = sorted(fine, \
        key=lambda x: x.ValuationRatios.PERatio, reverse=True)

    # take the top entries from our sorted collection
    return [ x.Symbol for x in sortedByPeRatio[:10] ]