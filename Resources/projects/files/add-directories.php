<?php
$getAddDirectoriesText = function($videoLink, $isDesktopDocs, $openProjectLink)
{        
    $navSide = $isDesktopDocs ? "left" : "right";
    $sectionName = $isDesktopDocs ? "QC (WORKSPACE)" : "WORKSPACE (WORKSPACE)";
    echo "
<p>Follow these steps to add a directory to a project:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    <li>In the {$navSide} navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-lab-explorer-icon.jpg'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the Explorer panel, expand the <span class='button-name'>{$sectionName}</span> section.</li>
    <li>Click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/new-directory-icon.png'> <span class='icon-name'>New Directory</span> icon.</li>
    <li>Enter a directory name and then press <span class='key-combinations'>Enter</span>.</li>
</ol>  
<img class='docs-image' src='{$videoLink}'>
    ";
}
?>
