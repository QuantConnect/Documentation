<p>Follow these steps to add a library to your project:</p>
<ol>
    <li><a href='<?=$openProjectLink?>'>Open the project</a>.</li>
    <? if (!$isCloud) { ?><li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li><? } ?>
    <li>In the Project panel, click <span class='button-name'>Add Library</span>.</li>
    <li>Click the <span class='field-name'>Choose a library...</span> field and then click a library from the drop-down menu.</li>
    <li>Click <span class='button-name'>Add Library</span>.</li>
    <p>The library files are added to your project. To view the files, in the right navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-lab-explorer-icon.jpg'> <span class='icon-name'>Explorer</span> icon.</p>
    <li class='python'>Import the library into your project to use the library.</li>
    <div class='python section-example-container'>
    <pre class='python'>from library import Library</pre>
    </div>
</ol>

