<p>The <code>TastytradeBrokerageModel</code> sets the buying power model based on the asset class of the security. The following table shows the default buying power model of each asset class:</p>

table class="qc-table table">
    <thead>
        <tr>
            <th>Asset Class</th>
            <th>Model</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Equities</td>
            <td><code>SecurityMarginModel</code></td>
        </tr>
        <tr>
            <td>Futures</td>
            <td><code>FutureMarginModel</code></td>
        </tr>
        <tr>
            <td>All Options Types</td>
            <td><code>OptionMarginModel</code></td>
        </tr>
    </tbody>
</table>

<p>If you have a margin account, the <code>TastytradeBrokerageModel</code> allows 2x leverage for Equities. The remaining asset classes are derivatives.</p>