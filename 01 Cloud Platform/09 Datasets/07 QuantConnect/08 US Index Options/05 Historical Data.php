<?
include(DOCS_RESOURCES."/data-feeds/historical-data/intro.php");
include(DOCS_RESOURCES."/data-feeds/historical-data/options-limits.html");

$csharpSubscription = 'var option = AddIndexOption("SPX", resolution);';
$pythonSubscription = 'option = self.add_index_option("SPX", resolution)';
include(DOCS_RESOURCES."/securities/resolutions/options-live-second.php");
?>
