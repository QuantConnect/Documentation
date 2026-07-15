<p>To avoid collaborators overwriting each other's changes, only one collaborator can edit a project at a time. This includes AI assistants that edit files on your behalf. When another collaborator or assistant has edit control of the project, a lock icon displays on the file tabs and the project files are read-only.</p>

<p>Follow these steps to request edit control of the project:</p>
<ol>
    <li><a href='<?=$localPlatform ? "/docs/v2/local-platform/projects/getting-started#04-Open-Projects" : "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" ?>'>Open the project</a>.</li>
    <? if ($localPlatform) { ?><li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'><span class='icon-name'> QuantConnect</span> icon.</li><? } ?>
    <li>In the Collaborate section of the Project panel, click <span class='button-name'>Request Edit Control</span>.</li>
</ol>

<p>After you take edit control, the text changes to <span class='page-section-name'>You have edit permission</span> and the lock icon is removed from the file tabs.</p>
