<?php
$getRemoveText = function($openProjectLink, $isLocal)
{
    echo "
<p>Follow these steps to remove a library from your project:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a> that contains the library you want to remove.</li>
    ";
    
    if ($isLocal)
    {
        echo "
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>
        ";
    }
    
    echo "
    <li>In the Project panel, hover over the library name and then click the <span class='icon-name'>trash can</span> icon that appears.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/delete-libraries.png'>
    <p>The library files are removed from your project.</p>
</ol>
    ";
}
?>
