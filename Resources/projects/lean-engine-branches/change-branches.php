<?php
$getChangeBranchesText = function($isDesktopDocs, $openProjectLink, $createProjectLink)
{        
    echo "
<p>Follow these steps to change the LEAN engine branch that runs your backtests and live trading algorithms:</p>

<ol>
    <li><a href='{$openProjectLink}'>Open a project</a>.</li>
    ";
  
    if ($isDesktopDocs)
    {
        echo "
<li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-lab-projects-tab.jpg'> <span class='icon-name'>Project</span> icon.</li>
        "; 
    }
  
    echo "
    <li>In the Project panel, click the <span class='field-name'>LEAN Engine</span> field and then click a branch from the drop-down menu.</li>
    <li><span class='qualifier'>(Optional)</span> Click <span class='button-name'>About Version</span> to display the branch description.</li>
    <li>If you want to always use the master branch, select the <span class='button-name'>Always use Master Branch</span> check box.</li>
    <li>Click <span class='button-name'>Select</span>.</li>
</ol>

<p>Changing the Lean engine branch only affects the current project. If you <a href='{$createProjectLink}'>create a new project</a>, the new project will use the master branch by default.</p>
    ";
}
?>
