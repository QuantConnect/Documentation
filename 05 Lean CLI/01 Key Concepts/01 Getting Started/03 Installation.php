<p>
    Run <code>pip install lean</code> in a terminal to install the latest version of the CLI.
</p>

<p>
    After installing the CLI, open a terminal in an empty directory and run <code>lean login</code> to log in to your QuantConnect account and then run <code>lean init</code> to create your first organization workspace.
    The <code>lean init</code> command downloads the latest configuration file and sample data from the <a href="https://github.com/QuantConnect/Lean" target="_blank">QuantConnect/Lean</a> repository.
    We recommend running all Lean CLI commands in your organization workspace directory.
</p>

<div class="cli section-example-container">
<pre>$ lean init
Downloading latest sample data from the Lean repository...
The following objects have been created:
- lean.json contains the configuration used when running the LEAN engine locally
- data/ contains the data that is used when running the LEAN engine locally
...</pre>
</div>

<? include(DOCS_RESOURCES."/cli/init/wsl.html"); ?>