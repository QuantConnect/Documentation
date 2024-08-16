<p>We give an arbitrary name (for example, "Smooth Apricot Chicken") to your backtest result files, but you can follow these steps to rename them:</p>
<ol>
	<? if (!is_null($link)) { ?>
	<li>Open the <a href='<?=$link?>'>backtest history</a> of the project.</li>
	<? } ?> 
    <li>Hover over the backtest you want to rename and then click the <span class='icon-name'>
pencil</span> icon that appears.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/rename-backtest-result.png' alt="Rename backtest navigation">
    <li>Enter the new backtest name and then click <span class='button-name'>OK</span>.</li>
</ol>

<p>To programmatically set the backtest name, call the <code class="csharp">SetName</code><code class="python">set_name</code> method.</p>

<div class="section-example-container">
<pre class="csharp">SetName("Backtest Name");</pre>
<pre class="python">self.set_name("Backtest Name")</pre>
</div>

<p>For more information, see <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/initialization#97-Set-Name-and-Tags'>Set Name and Tags</a>.</p>
