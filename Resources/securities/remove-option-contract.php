<p>To remove a contract subscription that you created with <code><?=$addContractMethodName?></code>, call the <code>RemoveOptionContract</code> method. This method is an alias for <code class="csharp">RemoveSecurity</code><code class="python">remove_security</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>RemoveOptionContract(<?=$cSharpVariableName?>);</pre>
    <pre class='python'>self.remove_option_contract(<?=$pythonVariableName?>)</pre>
</div>

<p>The <code>RemoveOptionContract</code> method cancels your open orders for the contract and liquidates your holdings.</p>
