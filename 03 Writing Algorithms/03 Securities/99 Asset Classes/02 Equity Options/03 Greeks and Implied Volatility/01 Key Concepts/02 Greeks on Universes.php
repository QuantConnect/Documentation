<? 
$addOptionPy = "self.add_option(\"SPY\")
";
$addOptionC = "AddOption(\"SPY\");
";
$filterLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data/universes#04-Filter-by-Implied-Volatility-and-Greeks";
$indicatorLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators";
$calculationMethod = "To calculate the values, we use <a href='/research/16977/greeks-and-iv-implementation/p1'>our implementation</a> of the forward tree pricing model, which accounts for the interest rate and dividend payments.";
include(DOCS_RESOURCES."/option-pricing/greek-universe.php"); 
?>
