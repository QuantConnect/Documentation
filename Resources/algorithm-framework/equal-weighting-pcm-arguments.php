<?php

$getPCMArgumentText = function($supportsPortfolioBias) {
    $result = "<p>The following table describes the arguments the model accepts:</p>";
	
    $result .= "
<table class=\"qc-table table\">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class=\"python\">rebalance</code><code class=\"csharp\">resolution</code></td>
	    <td><code class=\"csharp\">Resolution</code>
                 <span class=\"python\">Any of the following types:
                     <ul>
                         <li><code>timedelta</code></li>
                         <li><code>Resolution</code></li>
                         <li><a href=\"/docs/v2/writing-algorithms/scheduled-events#04-Date-Rules\">DateRules</a></li>
                         <li><code>None</code></li>
                         <li>A function</li>
                     </ul>
                 </span>
            </td>
	    <td><span class=\"csharp\">Rebalancing frequency</span><span class=\"python\">Rebalancing parameter. If it's a <code>timedelta</code>, <code>DateRules</code> or <code>Resolution</code>, it's converted into a function. If it's <code>None</code>, it's ignored. The function returns the next expected rebalance time for a given algorithm UTC DateTime. The function returns </span><span class=\"python\"><span class=\"python\"><code>None </code></span>if unknown, in which case the function will be called again in the
                              next loop. If the function returns the current time, the portfolio rebalances.</span></td>
            <td><code>Resolution.Daily</code></td>
        </tr>
";
    if ($supportsPortfolioBias)
    {
        $result .= "
        <tr>
            <td><code>portfolioBias</code></td>
	    <td><code>PortfolioBias </code></td>
            <td>The bias of the portfolio</td>
            <td><code>PortfolioBias.LongShort</code></td>
        </tr>
";
    }

    $result .= "
    </tbody>
</table>

<p class=\"csharp\">The following table describes the arguments that can replace the <code>resolution</code> argument:</p>


<table class=\"csharp qc-table table\">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>rebalancingDateRules</code></td>
	    <td><a href=\"/docs/v2/writing-algorithms/scheduled-events#04-Date-Rules\">IDateRules</a></td>
            <td>The date rules used to define the next expected rebalance time</td>
        </tr>
        <tr>
            <td><code>rebalancingFunc</code></td>
	    <td><code>Func&lt;DateTime, DateTime?&gt;</code></td>
            <td>For a given algorithm UTC DateTime returns the next expected rebalance time or null if unknown, in which case the function will be called again in the next loop. Returning current time will trigger rebalance. If null will be ignored</td>
        </tr>
        <tr>
            <td><code>rebalancingFunc</code></td>
	    <td><code>Func&lt;DateTime, DateTime&gt;</code></td>
            <td>For a given algorithm UTC DateTime returns the next expected rebalance UTC time. Returning current time will trigger rebalance. If null will be ignored</td>
        </tr>
        <tr>
            <td><code>timeSpan</code></td>
	    <td><code>TimeSpan </code></td>
            <td>Rebalancing frequency</td>
        </tr>
    </tbody>
</table>
";
	
    echo $result;
}

?>
