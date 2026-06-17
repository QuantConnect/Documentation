<?php
ob_start();
include(DOCS_RESOURCES."/datasets/data-point-attributes/eodhd/supported-macro-indicators.html");
echo str_replace(["language-cs", "language-python"], ["csharp", "python"], ob_get_clean());
?>
