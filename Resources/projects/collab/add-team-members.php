<p>You need to own the project to add team members to it.</p>

<p>Follow these steps to add team members to a project:</p>

<ol>
    <li><a href='/docs/v2<?=$localPlatform ? "/local-platform/projects/getting-started#04-Open-Projects" : "/cloud-platform/projects/getting-started#02-View-All-Projects" ?>'>Open the project</a>.</li>
    
    <? if ($localPlatform) { ?> <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li><? } ?>
    <li>In the Collaborate section of the Project panel, click <span class='button-name'>Add Collaborator</span>.</li>
    <li>Click the <span class='field-name'>Select User...</span> field and then click a member from the drop-down menu.</li>
    <li>If you want to give the member <a href='/docs/v2/cloud-platform/live-trading/algorithm-control'>control of the project's live deployments</a>, select the <span class='box-name'>Live Control</span> check box.</li>
    <li>Click <span class='button-name'>Add User</span>.</li>
    <p>The member you add receives an email with a link to the project.</p>
</ol>

<p>If the project has a <a href='/docs/v2<?=$localPlatform ? "/local-platform/projects/shared-libraries" : "/cloud-platform/projects/shared-libraries" ?>'>shared library</a>, the collaborator can access the project, but not the library. To grant them access to the library, add them as a collaborator to the library project.</p>
