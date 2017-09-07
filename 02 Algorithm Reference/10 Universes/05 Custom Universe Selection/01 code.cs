AddUniverse<NyseTopGainers>("myCustomUniverse", Resolution.Daily, nyseTopGainersList => {
      return from singleStockData in nyseTopGainersList 
             where singleStockData.Rank > 5
             select singleStockData.Symbol;
});