<?php
$getLibrariesText = function($isDesktopDocs, $addLibrariesLink, $createLibrariesLink)
{
    $location = $isDesktopDocs = ? "your workspace" : "the Algorithm Lab" ;
    $fileType = $isDesktopDocs = ? "private" : "public" ;
    $ide = $isDesktopDocs = ? "VS Code" : "the web IDE" ;
    echo "
<p>Libraries are reusable code files that you can import into any project for use in backtesting, research, and live trading. Use libraries to increase your development speed and save yourself from copy-pasting between projects. You can <a href='{$createLibrariesLink}'>create libraries</a> and <a href='{$addLibrariesLink}'>add them to your projects</a> using {$ide}. Your libraries are saved under the <span class='{$fileType}-directory-name'>Library</span> directory in {$location}.</p>
    ";
}
?>
