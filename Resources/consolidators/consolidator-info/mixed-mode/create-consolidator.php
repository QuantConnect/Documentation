<p>To create a mixed-mode consolidator, pass a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> object and an integer to the consolidator constructor. The consolidator time period must be greater than or equal to the resolution of the security subscription. For instance, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars.</p>

<div class='section-example-container'>
	<pre class='csharp'>_consolidator = new <?=$consolidatorClassName?>(<?=$this->numSamples?>, TimeSpan.<?=$this->timeSpanPeriod?>);</pre>
	<pre class='python'>self._consolidator = <?=$consolidatorClassName?>(<?=$this->numSamples?>, timedelta(<?=$this->timeDeltaPeriod?>))</pre>
</div>

<?php 
echo file_get_contents(DOCS_RESOURCES."/consolidators/time-period-start-time.html");
echo file_get_contents(DOCS_RESOURCES."/consolidators/time-period-end-time.html");
?>
