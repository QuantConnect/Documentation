<p>
    Follow these steps to create a new Python project:
</p>

<ol>
    <li>Open a terminal in one of your <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspaces</a>.</li>
    <li>Run <code>lean project-create --language python "&lt;projectName&gt;"</code> to create a new project named <span class="public-directory-name">&lt;projectName&gt;</span>. 
<div class="cli section-example-container">
<pre>$ lean project-create --language python "My Python Project"
Successfully created Python project 'My Python Project'</pre>
</div>
        This command creates the <span class="public-directory-name">. / &lt;projectName&gt;</span> directory and creates a simple <span class="public-file-name">main.py</span> file, a Python-based research notebook, a <a href="/docs/v2/lean-cli/initialization/configuration#04-Project-Configuration">project configuration file</a>, and editor configuration for PyCharm and VS Code.
    </li>
</ol>

<p>
    Follow these steps to create a new C# project:
</p>

<ol>
    <li>Open a terminal in one of your <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspaces</a>.</li>
    <li>Run <code>lean project-create --language csharp "</code><code><code>&lt;projectName&gt;</code>"</code> to create a new C# project named <span class="public-directory-name">&lt;projectName&gt;</span>. 
<div class="cli section-example-container">
<pre>$ lean project-create --language csharp "My CSharp Project"
Successfully created C# project 'My CSharp Project'</pre>
</div>
        This command creates the <span class="public-directory-name">. / &lt;projectName&gt;</span> directory and creates a simple <span class="public-file-name">Main.cs</span> file, a C#-based research notebook, a <a href="/docs/v2/lean-cli/initialization/configuration#04-Project-Configuration">project configuration file</a>, and editor configuration for Visual Studio, Rider, and VS Code.
    </li>
</ol>

<? include(DOCS_RESOURCES."/cli/project-name-rules.html");?>

<p>
    You can provide a project name containing forward slashes to create a project in a sub-directory.
    In case any of the given sub-directories does not exist yet, the CLI creates them for you.
</p>
