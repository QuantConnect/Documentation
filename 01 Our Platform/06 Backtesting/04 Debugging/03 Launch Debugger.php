<p>Follow these steps to launch the debugger:</p>
<ol>
    <li><a href="/docs/v2/our-platform/tutorials/projects/managing-projects#05-Open-Existing-Projects">Open the project</a> you want to debug.</li>
    <li>In your project's code files, add atleast one <a href='/docs/v2/our-platform/backtesting/debugging#02-Breakpoints'>breakpoint</a>.</li>
    <li>Click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/backtest-button.png'> <span class='icon-name'>Backtest</span> icon.</li>
    <li>If you want to set watch variables, follow these steps:</li>
    <ol>
    	<li>In the Debug panel, click the <span class='icon-name'>plus</span> icon.</li>
    	<li>Enter the name of the variable to watch and then click <span class='button-name'>OK</span>.</li>
    	<li>If you want to add more watch variables, continue from step 4.1.</li>
    	<li>If you want to update your watch variables, follow these steps:</li>
    	<ol>
    		<li>In the Debug panel, click the variable name that you want to update.</li>
    		<li>Enter the new variable name and then click <span class='button-name'>OK</span>.</li>
    	</ol>
    	<li>If you want to remove a watch variable, click the <span class='icon-name'>X</span> icon next to the watch variable that you want to remove.</li>
    </ol>
    <li>Click <span class='button-name'>Backtest</span> to start debugging.</li>
    <li>Control the debugger with the buttons in the Debug panel.</li>
    <p>The following table describes the functionality of the debugger buttons:</p>
    <?php include(DOCS_RESOURCES."/debugger-buttons-table.html"); ?>
</ol>
