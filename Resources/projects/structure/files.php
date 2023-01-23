<?php
$getFileStructureText = function($isDesktopDocs, $renameFilesLink)
{
    echo "
<p>New projects contain code files 
";
    if ($isDesktopDocs)
    {
        echo "
(<span class='private-file-name'>.py</span> or <span class='private-file-name'>.cs</span>), notebook files (<span class='private-file-name'>.ipynb</span>), and some configuration files (<span class='private-file-name'>.json</span>)";
    }
    else
    {
        echo "
(<span class='public-file-name'>.py</span> or <span class='public-file-name'>.cs</span>) and notebook files (<span class='public-file-name'>.ipynb</span>)";
    }
    echo ". Run backtests with code files and launch the Research Environment with notebook files. ";
    
    $intro = $isDesktopDocs ? "In QuantConnect Cloud, c" : "C" ;
    echo "{$intro}ode files can contain up to 64,000 characters and notebook files can be up to 128KB in size.";
    
    if ($isDesktopDocs)
    {
        echo "
In your local workspace, the code files and notebook files can be any size.
        ";
    }
    
    $location = $isDesktopDocs ? "VS Code" : "the web IDE" ;
    
    echo "
To keep files small, files can import code from other code files. To aid navigation, you can <a href='{$renameFilesLink}'>rename, move, and delete files</a> in {$location}. Notebook files include the JSON elements to display the input cells, but not the output cells.</p>
    ";
}
?>
