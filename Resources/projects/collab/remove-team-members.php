<?php
$getRemoveMemberText = function($openProjectLink, $isLocal)
{

    echo "
<p>Follow these steps to remove a team member from a project you own:</p>
<ol>
    <li><a href='{$openProjectLink}'>Open the project</a>.</li>
    ";
    
    if ($isLocal)
    {
        echo "
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>
        ";
    }
    
    echo "
    <li>In the Collaborate section of the Project panel, click the profile image of the team member.</li>
    <li>Click <span class='button-name'>Remove User</span>.</li>
</ol>

</p>To remove yourself as a collaborator from a project you don't own, <a href='/docs/v2/our-platform/projects/getting-started#11-Delete-Projects'>delete the project</a>.</p>

    ";
}
?>
