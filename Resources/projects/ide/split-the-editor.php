<?php
$getSplitText = function($isDesktopDocs, $openProjectLink, $gifLink)
{
    $navSide = $isDesktopDocs ? "left" : "right" ;
    echo "
<p>The editor can split horizontally and vertically to display multiple files at once. Follow these steps to split the editor:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open a project</a>.</li>
    <li>In the {$navSide} navigation bar, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/explorer-icon.png'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the <span class='page-section-name placeholder-text'>projectName</span> section, drag and drop the files you want to open.</li>
</ol>

<img class='docs-image' src='{$gifLink}'>
    ";
}
?>
