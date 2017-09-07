// Take the top 50 by dollar volume using coarse
// Then the top 10 by PERatio using fine
AddUniverse(
    coarse => {
        return (from c in coarse
            where c.Price > 10
            orderby c.DollarVolume descending
            select c.Symbol).Take(50);
    },
    fine => {
        return (from f in fine
            orderby f.ValuationRatios.PERatio descending
            select f.Symbol).Take(10);
    });