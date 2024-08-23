<p>To remove a contract subscription that you created with <?=$addContractMethodName?>, call the <code class="csharp">RemoveOptionContract</code><code class="python">remove_option_contract</code> method. This method is an alias for <code class="csharp">RemoveSecurity</code><code class="python">remove_security</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>RemoveOptionContract(<?=$cSharpVariableName?>);</pre>
    <pre class='python'>self.remove_option_contract(<?=$pythonVariableName?>)</pre>
</div>

<p>The <code class="csharp">RemoveOptionContract</code><code class="python">remove_option_contract</code> method cancels your open orders for the contract and liquidates your holdings.</p>
