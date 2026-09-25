<?
$hasParent = false; $hasSiblings = true;
$csharpUpdate = '// Move the stop loss.
var response = tickets[1].UpdateStopPrice(price * 0.985m, "Updated stop loss");
if (response.IsSuccess)
{
    Debug("Order updated successfully");
}';
$pythonUpdate = '# Move the stop loss.
response = tickets[1].update_stop_price(price * 0.985, "Updated stop loss")
if response.is_success:
    self.debug("Order updated successfully")';
include(DOCS_RESOURCES."/order-types/contingent-orders/update-orders.php");
?>
