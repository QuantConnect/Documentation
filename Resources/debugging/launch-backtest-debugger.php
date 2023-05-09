<?
$openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#04-Open-Projects";
$iconImg = $localPlatform ? "https://cdn.quantconnect.com/i/tu/local-platform-debug-icon.png" : "https://cdn.quantconnect.com/i/tu/debug-icon.png";
?>

<p>Follow these steps to launch the debugger:</p>
<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> you want to debug.</li>
    <li>In your project's code files, add at least one breakpoint.</li>
    <li>Click the <img class='inline-icon' src='<?=$iconImg?>' alt="Debug icon"> <span class='icon-name'>Debug</span> icon.</li>
</ol>

<p>If the Run and Debug panel is not open, it opens when the first breakpoint is hit.</p>
