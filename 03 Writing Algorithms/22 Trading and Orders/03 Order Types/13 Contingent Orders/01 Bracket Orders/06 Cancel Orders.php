<?
$hasParent = true; $hasSiblings = true;
$csharpCancel = '// Cancel the entry, which also cancels the take profit and the stop loss.
var response = tickets[0].Cancel("Cancel entry, which also cancels the take profit and the stop loss");
if (response.IsSuccess)
{
    Debug("Order successfully cancelled");
}';
$pythonCancel = '# Cancel the entry, which also cancels the take profit and the stop loss.
response = tickets[0].cancel("Cancel entry, which also cancels the take profit and the stop loss")
if response.is_success:
    self.debug("Order successfully cancelled")';
include(DOCS_RESOURCES."/order-types/contingent-orders/cancel-orders.php");
?>
