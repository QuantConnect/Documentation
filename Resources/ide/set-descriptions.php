<?php
$getSetDescriptionText = function($openProjectLink, $isDesktopDocs)
{
    echo "
<p>Follow these steps to set the project description:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    ";
    
    if ($isDesktopDocs)
    {
        echo "
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>
        ";
    }
    
    echo "
    <li>In the Project panel, hover over the project name and then click the <span class='icon-name'>pencil</span> icon that appears.</li>
    <img class='docs-image' style='max-height: 120px' src='https://cdn.quantconnect.com/i/tu/rename-project-1.png'>
    <li>In the <span class='field-name'>Description</span> field, enter the new project description and then click <span class='button-name'>Save Changes</span>.</li>
</ol>  
    ";
}
?>
