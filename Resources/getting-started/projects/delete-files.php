<?php
$getDeleteFileText = function($fileType, $openProjectLink, $isDesktopDocs=false)
{
    $navSide = $isDesktopDocs ? "left" : "right";
    $sectionName = $isDesktopDocs ? "<span class='placeholder-text'>projectName</span>" : "Project (Workspace)";
    echo "
<p>Follow these steps to delete a {$fileType} in a project:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    <li>In the {$navSide} navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/explorer-icon.png'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the Explorer panel, right-click the {$fileType} you want to delete and then click <span class='menu-name'>Delete Permanently</span>.</li>
    <li>Click <span class='button-name'>Delete</span>.</li>
</ol>
	";
}
?>
