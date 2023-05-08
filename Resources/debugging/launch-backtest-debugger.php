<?
$openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#04-Open-Projects";
?>

<p>Follow these steps to launch the debugger:</p>
<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> you want to debug.</li>
    <li>In your project's code files, add at least one breakpoint.</li>
    <li>Click the <? if ($localPlatform) { ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-platform-debug-icon.png' alt="Local debug icon">/<? } ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/debug-icon.png' alt="Cloud debug icon"> <span class='icon-name'>Debug</span> icon.</li>
</ol>

<p>If the Run and Debug panel is not open, it opens when the first breakpoint is hit.</p>
