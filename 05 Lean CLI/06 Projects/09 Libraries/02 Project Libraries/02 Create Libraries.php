<p>To create a library, open a terminal in one of your <a href="/docs/v2/lean-cli/initialization/organization-workspaces">organization workspaces</a> and then <a href="/docs/v2/lean-cli/projects/project-management#02-Create-Projects">create a project</a> in the <span class="public-file-name">Library</span> directory.</p>

<div class="csharp cli section-example-container">
<pre>$ lean project-create "Library/MyLibrary"
Restoring packages in 'Library\MyLibrary' to provide local autocomplete
Restored successfully
Successfully created C# project 'Library/MyLibrary'</pre>
</div>

<div class="python cli section-example-container">
<pre>$ lean project-create "Library/MyLibrary"
Successfully created Python project 'Library/MyLibrary'</pre>
</div>

<? include(DOCS_RESOURCES."/cli/python-library-naming-rules.html"); ?>

<p>The <code>lean project-create</code> command creates a new project based on your <a href='/docs/v2/lean-cli/projects/project-management#03-Set-the-Default-Language'>default programming language</a>. To create a <span class='csharp'>Python</span><span class='python'>C#</span> library, add the <code class='python'>--language csharp</code><code class='csharp'>--language python</code> option.</p>

<div class="python cli section-example-container">
<pre>$ lean project-create "Library/MyLibrary" --language csharp
Successfully created C# project 'Library/MyLibrary'
Restoring packages in 'Library\MyLibrary' to provide local autocomplete
Restored successfully
Successfully created C# project 'Library/MyLibrary'</pre>
</div>

<div class="csharp cli section-example-container">
<pre>$ lean project-create "Library/MyLibrary" --language python
Successfully created Python project 'Library/MyLibrary'</pre>
</div>