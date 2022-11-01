<?php
$getAddFilesText = function($videoLink, $isDesktopDocs, $openProjectLink)
{        
    $navSide = $isDesktopDocs ? "left" : "right";
    $sectionName = $isDesktopDocs ? "<span class='placeholder-text'>projectName</span>" : "Project (Workspace)";
    echo "
<p>Follow these steps to a add file to a project:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    <li>In the {$navSide} navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/explorer-icon.png'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the Explorer panel, expand the <span class='button-name'>{$sectionName}</span> section.</li>
    <li>Click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/new-file-icon.png'> <span class='icon-name'>New File</span> icon.</li>
    <li>Enter a file name and extension.</li>
    <li>Press <span class='key-combinations'>Enter</span>.</li>
</ol>  
<img class='docs-image' src='{$videoLink}'>
    ";
}
?>
