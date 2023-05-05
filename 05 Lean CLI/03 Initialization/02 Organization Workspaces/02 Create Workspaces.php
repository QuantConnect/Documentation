<p>
    To create an organization workspace for one of your organizations, open a terminal in an empty directory, run <code>lean init</code> and then select an organization to link with the organization workspace.
    This command scaffolds a standard directory structure containing a <span class='public-file-name'>data</span> directory and a <a href="/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration">Lean configuration file</a>, both of which are required to run the LEAN engine locally.
</p>

<? include(DOCS_RESOURCES."/cli/init/wsl.html"); ?>

<p>To set the default language of new projects in a new organization workspace, run <code>lean init --language &lt;value&gt;</code> where the <code>&lt;value&gt;</code> is <code>python</code> or <code>csharp</code>.</p>