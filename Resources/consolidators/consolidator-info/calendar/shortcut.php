<p>You can also use the <code class='csharp'>Consolidate</code><code class='python'>consolidate</code> helper method to create calendar consolidators and register them for automatic updates.</p>

<div class='section-example-container'>
    <pre class='csharp'>_consolidator = Consolidate<?= $consolidationHandlerType == "QuoteBar" ? "&lt;QuoteBar&gt;" : "" ?>(_symbol, Calendar.Weekly, <?=$this->shortCutTickTypeArg?>ConsolidationHandler);</pre>
    <pre class='python'>self._consolidator = self.consolidate(self._symbol, Calendar.WEEKLY, <?=$this->shortCutTickTypeArg?>self._consolidation_handler)</pre>
</div>

<?php include(DOCS_RESOURCES."/consolidators/get-shortcut-text.php"); ?>
