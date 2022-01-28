<p>The debugger is a built-in tool to help you debug coding errors while backtesting. The debugger enables you to slow down the code execution, step through the program line-by-line, and inspect the variables to understand the internal state of the program. The debugger functions by using breakpoints, watch variables, and step controls. Breakpoints are lines of code in your algorithm where execution will pause. Watch variables are the variables in your algorithm that you want to inspect during execution. The values of watch variables are formated in the IDE to improve readability. For example, when you watch a variable that references a DataFrame, the debugger represents the variable value as the following:</p>

<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/debugger-dataframe.png">

<p>After you <a href="/docs/v2/our-platform/tutorials/backtesting/debugging">launch the debugger</a>, you can control program execution with the buttons described in the following table:</p>

<?php include(DOCS_RESOURCES."/debugger-buttons-table.html"); ?>
