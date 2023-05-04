<p>You need to have added a member to the project to toggle their live control of the project.</p>

<p>Follow these steps to enable and disable live control for a team member:</p>
<ol>
    <li><a href='<?=$localPlatform ? "/docs/v2/local-platform/projects/getting-started#04-Open-Projects" : "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" ?>'>Open the project</a>.</li>
    <? if ($localPlatform) { ?><li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'><span class='icon-name'> QuantConnect</span> icon.</li><? } ?>
    <li>In the Collaborate section of the Project panel, click the profile image of the team member.</li>
    <li>Click the <span class='box-name'>Live Control</span> check box.</li>
    <li>Click <span class='button-name'>Save Changes</span>.</li>
</ol>
