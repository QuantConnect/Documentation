<?
$hasParent = true; $hasSiblings = false;
$csharpCancel = '// Cancel the parent, which also cancels the child orders.
var response = tickets[0].Cancel("Cancel parent, which also cancels the child orders");
if (response.IsSuccess)
{
    Debug("Order successfully cancelled");
}';
$pythonCancel = '# Cancel the parent, which also cancels the child orders.
response = tickets[0].cancel("Cancel parent, which also cancels the child orders")
if response.is_success:
    self.debug("Order successfully cancelled")';
include(DOCS_RESOURCES."/order-types/contingent-orders/cancel-orders.php");
?>
