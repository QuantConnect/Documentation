<?php
$getRenameFileText = function($fileType, $openProjectLink, $isDesktopDocs=false, $videoLink=null)
{
    $navSide = $isDesktopDocs ? "left" : "right";
    $sectionName = $isDesktopDocs ? "<span class='placeholder-text'>projectName</span>" : "Project (Workspace)";
    echo "
<p>Follow these steps to rename a {$fileType} in a project:</p>

<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    <li>In the {$navSide} navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/explorer-icon.png'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the Explorer panel, right-click the {$fileType} you want to rename and then click <span class='menu-name'>Rename</span>.</li>
    <li>Enter the new name and then press <span class='button-name'>Enter</span>.</li>
</ol>
	";
	
	if (!is_null($videoLink))
	{
		echo "
<img class='docs-image' src='{$videoLink}'>
		";
	}
}
?>
