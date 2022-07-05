<?php 
include(DOCS_RESOURCES."/glossary.php");
echo "<p>";
echo $definitionByTerm['Sharpe ratio'];
echo "</p>";
?>

<?php
$a = array("a" => "b");
foreach ($a as $key => $value)
{
    echo "{$key} => {$value}";
}
?>
