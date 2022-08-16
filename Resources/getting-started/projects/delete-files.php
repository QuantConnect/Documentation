<?php
$getDeleteFileText = function($fileType)
{
	echo "
<p>Follow these steps to delete a {$fileType} in a project:</p>

<ol>
    <li><a href='/docs/v2/our-platform/projects/getting-started#02-View-All-Projects'>Open the project</a>.</li>
    <li>In the right navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/explorer-icon.png'> <span class='icon-name'>Explorer</span> icon.</li>
    <li>In the Explorer panel, right-click the {$fileType} you want to delete and then click <span class='menu-name'>Delete Permanently</span>.</li>
    <li>Click <span class='button-name'>Delete</span>.</li>
</ol>
	";
}
?>