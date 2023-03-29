<p>
    Run <code>pip install lean</code> in a terminal to install the latest version of the CLI.
</p>
<p>
   Open a terminal and run <code> lean login</code>. Enter userId and token.<a href="https://www.quantconnect.com/docs/v2/cloud-platform/community/profile#09-Request-API-Token" target="_blank">Request a token></a>
</p>
<p>
    After authentication, open a terminal in an empty directory and run <code>lean init</code> to create your first workspace.
    The command downloads the latest configuration file and sample data from the <a href="https://github.com/QuantConnect/Lean" target="_blank">QuantConnect/Lean</a> repository.
    We recommend running all Lean CLI commands in your workspace directory.
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