<?
include(DOCS_RESOURCES."/data-feeds/historical-data/intro.php");
include(DOCS_RESOURCES."/data-feeds/historical-data/options-limits.html");

$csharpSubscription = 'var option = AddOption("SPY", resolution);';
$pythonSubscription = 'option = self.add_option("SPY", resolution)';
include(DOCS_RESOURCES."/securities/resolutions/options-live-second.php");
?>
