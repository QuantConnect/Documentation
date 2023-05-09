<table class="qc-table table">
    <thead>
        <tr>
            <th>Security Type</th>
            <th>Account Type</th>
            <th>Settlement Model</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Equity</td>
            <td>Cash</td>
            <td><a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#03-Delayed-Model'>DelayedSettlementModel</a> with the <? if ($includeLinks) {?><a href='/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts#03-Default-Behavior'>default settlement rules</a><? } else { ?>default settlement rules<? } ?></td>
        </tr>
		<tr>
            <td>Option</td>
            <td>Cash</td>
            <td><a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#03-Delayed-Model'>DelayedSettlementModel</a> with the <? if ($includeLinks) {?><a href='/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts#03-Default-Behavior'>default settlement rules</a><? } else { ?>default settlement rules<? } ?></td>
        </tr>
        <tr>
            <td>Future</td>
            <td>Any</td>
            <td><a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#05-Future-Model'>FutureSettlementModel</a></a></td>
        </tr>
    </tbody>
</table>

<p>For all other cases, the <code><?=$brokerageModelName?></code> uses the <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#02-Immediate-Model'>ImmediateSettlementModel</a>.</p>
