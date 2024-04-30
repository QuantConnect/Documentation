<p>You can also use the <code>Consolidate</code> helper method to create calendar consolidators and register them for automatic updates.</p>

<div class='section-example-container'>
    <pre class='csharp'>_consolidator = Consolidate<?= $consolidationHandlerType == "QuoteBar" ? "&lt;QuoteBar&gt;" : "" ?>(_symbol, Calendar.Weekly, <?=$this->shortCutTickTypeArg?>ConsolidationHandler);</pre>
    <pre class='python'>self.consolidator = self.Consolidate(self._symbol, Calendar.Weekly, <?=$this->shortCutTickTypeArg?>self.consolidation_handler)</pre>
</div>

<?php include(DOCS_RESOURCES."/consolidators/get-shortcut-text.php"); ?>
