<?
$hasParent = true; $hasSiblings = false;
$csharpUpdate = '// Move the parent limit price.
var response = tickets[0].UpdateLimitPrice(price * 0.995m, "Updated parent limit price");
if (response.IsSuccess)
{
    Debug("Order updated successfully");
}';
$pythonUpdate = '# Move the parent limit price.
response = tickets[0].update_limit_price(price * 0.995, "Updated parent limit price")
if response.is_success:
    self.debug("Order updated successfully")';
include(DOCS_RESOURCES."/order-types/contingent-orders/update-orders.php");
?>
