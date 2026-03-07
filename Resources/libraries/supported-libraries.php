<style>
.env-button-group { margin-bottom: 10px; }
.env-button {
    padding: 6px 16px;
    margin-right: 5px;
    border: 1px solid #ccc;
    background: #f5f5f5;
    cursor: pointer;
    border-radius: 4px;
}
.env-button.active {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
}
</style>
<div id="supported-libraries-container"></div>
<script>
<?
$cdnUrl = 'https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/environment-packages-';
echo "const defined_python_data = " . file_get_contents($cdnUrl . "python.json") . ";\n";
echo "const defined_csharp_data = " . file_get_contents($cdnUrl . "csharp.json") . ";\n";
include(DOCS_RESOURCES."/libraries/supported-libraries.js");
?>

renderLibrariesWithButtons('supported-libraries-container');
</script>
