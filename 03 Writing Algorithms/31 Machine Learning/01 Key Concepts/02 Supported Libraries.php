<style>
.centered {text-align: center; }
</style>

<p>LEAN supports several machine learning libraries. You can import these packages and use them in your algorithms.</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th width="20%">Name</th>
            <th width="15%">Version</th>
            <th width="15%">Language</th>
            <th>Import Statement</th>
            <th width="5%">Example</th>
        </tr>
    </thead>
<tbody id="ml-libraries-container">
</tbody>
</table>

<script>
<?
$cdnUrl = 'https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/environment-packages-';
echo "const defined_python_data = " . file_get_contents($cdnUrl . "python.json") . ";\n";
echo "const defined_csharp_data = " . file_get_contents($cdnUrl . "csharp.json") . ";\n";
include(DOCS_RESOURCES."/libraries/ml-libraries.js");
?>

renderMlLibraries('ml-libraries-container');
</script>
