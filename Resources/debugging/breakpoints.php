<? 
    $isBacktest = DOCS_URL(1) == "backtesting";
    $location = $isBacktest ? "algorithm" : "notebook";
?>

<p>Breakpoints are lines in your <?=$location ?> where execution pauses. You need at least one breakpoint in your <?=$isBacktest ? "code files" : "notebook" ?> to start the debugger. <a href='/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects'>Open a project</a> to start adjusting its breakpoints.</p>

<h4>Add Breakpoints</h4>

<? if ($isBacktest) { ?> 
<p>Click to the left of a line number to add a breakpoint on that line.</p>
<img class='python docs-image' src='https://cdn.quantconnect.com/i/tu/set-break-point-2.gif' alt="Add breakpoint">
<img class='csharp docs-image' src='https://cdn.quantconnect.com/i/tu/set-breakpoint-c-sharp.gif' alt="Add breakpoint">
<? } else { ?>
<p>Click to the left of a line to add a breakpoint on that line.</p>
<img class='python docs-image' src='https://cdn.quantconnect.com/i/tu/research-environment-breakpoint.gif' alt="Add breakpoint">
<img class='csharp docs-image' src='https://cdn.quantconnect.com/i/tu/research-environment-breakpoint-c.gif' alt="Add breakpoint">
<? } ?>

<h4>Edit Breakpoint Conditions</h4>
<p>Follow these steps to customize what happens when a breakpoint is hit:</p>
<ol>
    <li>Right-click the breakpoint and then click <span class='menu-name'>Edit Breakpoint...</span>.</li>
    <li>Click one of the options in the following table:</li>
</ol>
<table class='qc-table table'>
    <thead>
        <tr>
            <th>Option</th>
            <th>Additional Steps</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class='menu-name'>Expression</span></td>
            <td>Enter an expression and then press <span class='key-combinations'>Enter</span>.</td>
            <td>The breakpoint only pauses the <?=$location ?> when the expression is true.</td>
        </tr>
        <tr>
            <td><span class='menu-name'>Hit Count</span></td>
            <td>Enter an integer and then press <span class='key-combinations'>Enter</span>. </td>
            <td>The breakpoint doesn't pause the <?=$location ?> until its hit the number of times you specify.</td>
        </tr>
    </tbody>
</table>

<h4>Enable and Disable Breakpoints</h4>
<p>To enable a breakpoint, right-click it and then click <span class='menu-name'>Enable Breakpoint</span>.</p>
<p>To disable a breakpoint, right-click it and then click <span class='menu-name'>Disable Breakpoint</span>.</p>
<p>Follow these steps to enable and disable all breakpoints:</p>
<ol>
    <li>In the right navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/run-and-debug-icon.png' alt="Run &amp; debug icon"> <span class='icon-name'>Run and Debug</span> icon. </li>
    <li>In the Debug panel, hover over the <span class='page-section-name'>Breakpoints</span> section and then click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/disable-all-breakpoints-icon.png'> <span class='icon-name'>Toggle Active Breakpoints</span> icon.</li>
</ol>

<h4>Remove Breakpoints</h4>
<p>To remove a breakpoint, right-click it and then click <span class='menu-name'>Remove Breakpoint</span>.</p>
<p>Follow these steps to remove all breakpoints:</p>
<ol>
    <li>In the right navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/run-and-debug-icon.png' alt="Run &amp; debug icon"> <span class='icon-name'>Run and Debug</span> icon. </li>
    <li>In the Debug panel, hover over the <span class='page-section-name'>Breakpoints</span> section and then click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/remove-all-breakpoints-icon.png'> <span class='icon-name'>Remove All Breakpoints</span> icon.</li>
</ol>
