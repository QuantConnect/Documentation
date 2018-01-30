// Take the top 50 by dollar volume using coarse 
AddUniverse(coarse => {
    return (from c in coarse
	    where c.Price > 10
	    orderby c.DollarVolume descending 
            select c.Symbol).Take(50);
});