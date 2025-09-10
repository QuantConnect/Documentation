<? 
$csharpOrder = 'TrailingStopOrder("SPY", -100, 0.01m, trailingAsPercentage: true, asynchronous: true);';
$pythonOrder = 'self.trailing_stop_order("SPY", -100, 0.01, trailing_as_percentage=True, asynchronous=True)';
include(DOCS_RESOURCES."/trading-and-orders/asynchronous-orders.php"); 
?>