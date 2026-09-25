<?
$hasParent = false; $hasSiblings = true;
$csharpCancel = '// Cancel the take profit, which also cancels the stop loss.
var response = tickets[0].Cancel("Cancel take profit, which also cancels the stop loss");
if (response.IsSuccess)
{
    Debug("Order successfully cancelled");
}';
$pythonCancel = '# Cancel the take profit, which also cancels the stop loss.
response = tickets[0].cancel("Cancel take profit, which also cancels the stop loss")
if response.is_success:
    self.debug("Order successfully cancelled")';
include(DOCS_RESOURCES."/order-types/contingent-orders/cancel-orders.php");
?>
