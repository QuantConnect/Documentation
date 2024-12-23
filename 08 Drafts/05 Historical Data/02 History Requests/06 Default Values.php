<? 
$envName = $writingAlgorithms ? "algorithm" : "notebook";
?>


<p>The following table describes the assumptions of the History API:</p>
<table class='table qc-table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Assumption</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Resolution</td>
            <td>LEAN guesses the resolution you request by looking at the securities you already have in your <?=$envName?>. If you have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, the history request uses the same resolution as the subscription. If you don't have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is the default.</td>
        </tr>
        <tr class='csharp'>
            <td>Bar type</td>
            <td>If you don't specify a type for the history request, <code>TradeBar</code> is the default. If the asset you request data for doesn't have <code>TradeBar</code> data, specify the <code>QuoteBar</code> type to receive history.</td>
        </tr>
    </tbody>
</table>
