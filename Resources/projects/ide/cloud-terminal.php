<p>The console panel at the bottom of the IDE provides some helpful information while you're developing algorithms.</p>

<? if ($cloudPlatform)  { ?>
<h4>Cloud Terminal</h4>
<p>The <span class='tab-name'>Ask Mia</span> tab of the panel shows the API messages, errors, and the logs from your algorithms.</p>
<img class='docs-image' src='<?=$cloudTermianlImage?>' alt="Termianl interface">
<p>To clear the cloud terminal, click the <span class='button-name'>Clear Logs</span> icon in the top-right corner of the panel.</p>

<h4>Ask Mia</h4>
<p>The <span class='tab-name'>Ask Mia</span> tab of the panel is where you can interact with our AI assistant, Mia.</p>
<img src="https://cdn.quantconnect.com/i/tu/ask-mia.png" class="docs-image"/>
<p>Mia provides contextual assistance to most issues you may encounter when developing a strategy, including build errors, API methods, and best coding practices. It has been trained on hundreds of algorithms and thousands of documentation pages.</p>
<p>To clear the cloud terminal, click the <span class='button-name'>Clear Mia Chat</span> icon in the top-right corner of the panel.</p>

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



