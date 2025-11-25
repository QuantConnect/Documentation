<p>The console panel at the bottom of the IDE provides some helpful information while you're developing algorithms.</p>

<? if ($cloudPlatform)  { ?>
<h4>Cloud Terminal</h4>
<p>The <span class='tab-name'>Cloud Terminal</span> tab of the panel shows the API messages, errors, and the logs from your algorithms.</p>
<img class='docs-image' src='<?=$cloudTermianlImage?>' alt="Termianl interface">
<p>To clear the Cloud Terminal, click the <span class='button-name'>Clear Logs</span> icon in the top-right corner of the panel.</p>

<h4>Problems</h4>
<p>The <span class='tab-name'>Problems</span> tab of the panel highlights the coding errors in your algorithms.</p>
<img class='python docs-image' src='<?=$problemsImagePy?>' alt="Compilation error in code snippets">
<img class='csharp docs-image' src='<?=$problemsImageC?>' alt="Compilation error in code snippets">
<? } else { ?>
<h4>Problems</h4>
<p>The <span class='tab-name'>Problems</span> tab of the panel highlights the coding errors in your algorithms.</p>
<img class='python docs-image' src='<?=$problemsImagePy?>' alt="Compilation error in code snippets">
<img class='csharp docs-image' src='<?=$problemsImageC?>' alt="Compilation error in code snippets">

<h4>Terminal</h4>
<p>The <span class='tab-name'>Terminal</span> tab of the panel serves as a command line interface in the directory of your project.</p>
<? } ?>



