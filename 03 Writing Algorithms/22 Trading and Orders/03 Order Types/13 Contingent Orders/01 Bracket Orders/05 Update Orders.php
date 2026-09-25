<?
$hasParent = true; $hasSiblings = true;
$csharpUpdate = '// Move the take profit.
var response = tickets[1].UpdateLimitPrice(price * 1.03m, "Updated take profit");
if (response.IsSuccess)
{
    Debug("Order updated successfully");
}';
$pythonUpdate = '# Move the take profit.
response = tickets[1].update_limit_price(price * 1.03, "Updated take profit")
if response.is_success:
    self.debug("Order updated successfully")';
include(DOCS_RESOURCES."/order-types/contingent-orders/update-orders.php");
?>
