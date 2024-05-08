<p>To set the time period for the consolidator, you can use either a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> or <code>Resolution</code> object. The consolidator time period must be greater than or equal to the resolution of the security subscription. For instance, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars.</p>
<ul>
	<li class='python'><code>timedelta</code> Periods</li>
	<li class='csharp'><code>TimeSpan</code> Periods</li>
		
	<div class='section-example-container'>
		<pre class='csharp'>_consolidator = new <?=$consolidatorClassName?>(TimeSpan.<?=$this->timeSpanPeriod?>);
// Aliases:
// _consolidator = CreateConsolidator(TimeSpan.<?=$this->timeSpanPeriod?>, typeof(<?=$typeOf?>)<?=$this->createConsolidatorExtraArgs?>);
// _consolidator = ResolveConsolidator(_symbol, TimeSpan.<?=$this->timeSpanPeriod?><?=$this->resolveConsolidatorExtraArgsC?>);</pre>
			<pre class='python'>self._consolidator = <?=$consolidatorClassName?>(timedelta(<?=$this->timeDeltaPeriod?>))
# Aliases:
# self._consolidator = self.create_consolidator(timedelta(<?=$this->timeDeltaPeriod?>), <?=$typeOf?><?=$this->createConsolidatorExtraArgs?>)
# self._consolidator = self.resolve_consolidator(self._symbol, timedelta(<?=$this->timeDeltaPeriod?>)<?=$this->resolveConsolidatorExtraArgsPy?>)</pre>
	</div>

	<?php include(DOCS_RESOURCES."/consolidators/time-period-start-time.html");?>

	<li><code>Resolution</code> Periods</li>
	<p>The <code>Resolution</code> enumeration has the following members:</p>
	<div data-tree='QuantConnect.Resolution'></div>


	<div class='section-example-container'>
		<pre class='csharp'><?=$this->resolutionArgExtraExamplesC?>_consolidator = ResolveConsolidator(_symbol, Resolution.<?=$this->resolutionPeriod?>);</pre>
		<pre class='python'><?=$this->resolutionArgExtraExamplesPy?>self._consolidator = self.resolve_consolidator(self._symbol, Resolution.<?=$this->resolutionPeriod?>)</pre>
	</div>
</ul>

<?php if ($typeOf == "TradeBar" || $typeOf == "QuoteBar") { ?><p>If the security subscription in your algorithm provides <code>TradeBar</code> and <code>QuoteBar</code> data, <code class="csharp">ResolveConsolidator</code><code class="python">resolve_consolidator</code> returns a <code>TradeBarConsolidator</code>.</p><?php } ?>

<?php include(DOCS_RESOURCES."/consolidators/time-period-end-time.html"); ?>
