<p>The console panel at the bottom of the IDE shows API messages, errors, and the logs from your algorithms.</p>
<img class='docs-image' src='<?=$cloudTermianlImage?>' alt="Termianl interface">

<? if ($cloudPlatform)  { ?>
<p>The <span class='tab-name'>Ask Mia</span> tab of the panel is where you can interact with our AI assistant, Mia.</p>
<img src="https://cdn.quantconnect.com/i/tu/ask-mia.png" class="docs-image"/>
<p>Mia provides contextual assistance to most issues you may encounter when developing a strategy, including build errors, API methods, and best coding practices. It has been trained on hundreds of algorithms and thousands of documentation pages.</p>
<? } ?>

<p>The <span class='tab-name'>Problems</span> tab of the panel highlights the coding errors in your algorithms.</p>
<img class='python docs-image' src='<?=$problemsImagePy?>' alt="Compilation error in code snippets">
<img class='csharp docs-image' src='<?=$problemsImageC?>' alt="Compilation error in code snippets">

