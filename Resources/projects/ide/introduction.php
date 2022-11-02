<?php
$getIntroText = function($isDesktopDocs, $openProjectLink)
{
    $ideName = $isDesktopDocs ? "VS Code" : "web" ;
    echo "
<p>The {$ideName} Integrated Development Environment (IDE) lets you work on research notebooks and develop algorithms for backtesting and live trading. When you <a href='{$openProjectLink}'>open a project</a>, the IDE automatically displays. 
    ";
   
    if (!$isDesktopDocs)
    {
        echo "
The web IDE enables you to access your trading algorithms from anywhere in the world with just an internet connection and a browser.        
        ";
    }
    echo "
 If you prefer to use a different IDE, the <a href='/docs/v2/lean-cli'>CLI</a> allows you to develop locally in your preferred IDE.</p>
    ";
}
?>
