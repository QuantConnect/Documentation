<? 
$addOptionPy = "self.add_index_option(\"SPX\")
";
$addOptionC = "AddIndexOption(\"SPX\");
";
$filterLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/requesting-data/universes#04-Filter-by-Implied-Volatility-and-Greeks";
$indicatorLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators";
$calculationMethod = "To calculate the values, we use <a href='/research/16977/greeks-and-iv-implementation/p1'>our implementation</a> of the Black-Scholes pricing model, which accounts for the interest rate and dividend payments when applicable. For example, VIX doesn't have dividends. We use SPY for SPX and QQQ for NDX.";
include(DOCS_RESOURCES."/option-pricing/greek-universe.php"); 
?>
