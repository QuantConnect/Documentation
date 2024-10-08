<p>While your cloud algorithms run, you can liquidate their positions and stop their exeuction.</p>

<h4>Liquidate Positions</h4>
<?php
include(DOCS_RESOURCES."/trading-and-orders/liquidate-positions.php");
?>

<p>To stop an algorithm, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean cloud live liquidate "My Project"</code>.</p>
<div class="cli section-example-container">
<pre>$ lean cloud live liquidate "My Project"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-cloud-live-liquidate#04-Options'>Options</a>.</p>

<h4>Stop Algorithms</h4>
<?
include(DOCS_RESOURCES."/trading-and-orders/stop-algorithm.php");
?>

<p>To stop an algorithm, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean cloud live stop "My Project"</code>.</p>
<div class="cli section-example-container">
<pre>$ lean cloud live stop "My Project"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-cloud-live-stop#04-Options'>Options</a>.</p>

<h4>Send Commands</h4>
<p>To send <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands'>commands</a> to your algorithm, open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project and then run <code>lean cloud live cloud command "My Project" --data "&lt;payload&gt;"</code>.

<div class="cli section-example-container">
<pre>$ lean cloud live command "My Project" --data "{'ticker': 'AAPL', 'quantity': 1}"</pre>
</div>

<p>
	The preceding line will run the <code class='python'>on_command</code><code class='csharp'>OnCommand</code> method of your algorithm. 
	If you wrap the logic in a <code>Command</code> class in your algorithm, include a <code>$type</code> key in the payload and set the value to be the name of the class.
</p>

<div class="cli section-example-container">
<pre>$ lean cloud live command "My Project" --data "{'$type': 'MyCommand', 'ticker': 'AAPL', 'quantity': 1}"</pre>
</div>

<p>If you run the command in PowerShell, use <code>`$type</code> instead of just <code>$type</code>.</p>
<div class="cli section-example-container">
<pre>$ lean cloud live command "My Project" --data "{'`$type': 'MyCommand', 'ticker': 'AAPL', 'quantity': 1}"</pre>
</div>

<p>For more information about the command options, see <a href='/docs/v2/lean-cli/api-reference/lean-cloud-live-command#04-Options'>Options</a>.</p>
