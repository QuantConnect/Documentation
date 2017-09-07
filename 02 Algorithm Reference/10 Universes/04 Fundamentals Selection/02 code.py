# In Initialize:
self.AddUniverse(self.CoarseSelectionFunction, self.FineSelectionFunction)

def CoarseSelectionFunction(self, coarse):
    '''Take the top 50 by dollar volume using coarse'''
    # sort descending by daily dollar volume
    sortedByDollarVolume = sorted(coarse, \
        key=lambda x: x.DollarVolume, reverse=True) 

    # we need to return only the symbol objects
    list = List[Symbol]()
    for x in sortedByDollarVolume[:50]: list.Add(x.Symbol)
    return list

def FineSelectionFunction(self, fine):
    '''sort the data by P/E ratio and take the top 'NumberOfSymbolsFine' '''
    # sort descending by P/E ratio
    sortedByPeRatio = sorted(fine, key=lambda x: x.ValuationRatios.PERatio, reverse=True)

    # take the top entries from our sorted collection
    topFine = sortedByPeRatio[:self.__numberOfSymbolsFine]

    list = List[Symbol]()
    for x in topFine:
        list.Add(x.Symbol)

    return list