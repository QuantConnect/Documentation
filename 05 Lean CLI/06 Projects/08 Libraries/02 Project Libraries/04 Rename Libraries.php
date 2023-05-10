<p>Follow these steps to rename a library:</p>

<ol>
    <li>Open the <a href="/docs/v2/lean-cli/initialization/organization-workspaces">organization workspaces</a> where you store the library.</li>
    <li>In the <span class='public-directory-name'>Library</span> directory, rename the library project.</li>
    <? include(DOCS_RESOURCES."/cli/python-library-naming-rules.html"); ?>
    <li>If you have a copy of the library in QuantConnect Cloud, open a terminal in your organization workspace and push the library project.</li>
    <div class="cli section-example-container">
        <pre>$ lean cloud push --project "Library/MySpecialLibrary"
[1/1] Pushing 'Library\MySpecialLibrary'
Renaming project in cloud from 'Library/MyLibrary' to 'Library/MySpecialLibrary'
Successfully updated name, files, and libraries for 'Library/MyLibrary'</pre>
    </div>
</ol>
