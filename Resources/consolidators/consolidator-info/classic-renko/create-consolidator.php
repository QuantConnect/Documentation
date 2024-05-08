<p>To create a classic Renko consolidator, pass the bar size to the <code>ClassicRenkoConsolidator</code> constructor.</p>

<div class='section-example-container'>
	<pre class='csharp'>// Create a Classic Renko consolidator that emits a bar when the price moves $1
_consolidator = new ClassicRenkoConsolidator(1m);</pre>
	<pre class='python'># Create a Classic Renko consolidator that emits a bar when the price moves $1
self._consolidator = ClassicRenkoConsolidator(1)</pre>
</div>
		

<p>The <code>ClassicRenkoConsolidator</code> has the following default behavior:</p>
<ul>
    <li>It uses the <code class="csharp">Value</code><code class="python">value</code> property of the <code>IBaseData</code> object it receives to build the Renko bars</li>
    <li>It ignores the volume of the input data</li>
    <li>It enforces the open and close of each bar to be a multiple of the bar size</li>
</ul>

<?=$this->extraExamples?>

<p>To relax the requirement that the open and close of the Renko bars must be a multiple of bar size, disable the <code class="csharp">evenBars</code><code class="python">even_bars</code> argument. If you disable <code class="csharp">evenBars</code><code class="python">even_bars</code>, the open value of the first Renko bar is set to the first value from the <code>selector</code>. The following opening and closing Renko bar values are all multiples of the first value from the <code>selector</code></p>

<div class='section-example-container'>
	<pre class='python'>self._consolidator = ClassicRenkoConsolidator(1, even_bars=False)</pre>
	<pre class='csharp'>_consolidator = new ClassicRenkoConsolidator(1m, evenBars: false);</pre>
</div>
