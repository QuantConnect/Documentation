<p>To create a classic Range consolidator, pass the bar size to the <code>ClassicRangeConsolidator</code> constructor.</p>

<div class='section-example-container'>
	<pre class='csharp'>// Create a Classic Range consolidator that emits a bar when the price range moves $1
_consolidator = new ClassicRangeConsolidator(1m);</pre>
	<pre class='python'># Create a Classic Range consolidator that emits a bar when the price range moves $1
self._consolidator = ClassicRangeConsolidator(1)</pre>
</div>
		

<p>The <code>ClassicRangeConsolidator</code> has the following default behavior:</p>
<ul>
    <li>It uses the <code>Value</code> property of the <code>IBaseData</code> object it receives to build the Range bars</li>
    <li>It ignores the volume of the input data</li>
    <li>It enforces the high and low of each bar to be a multiple of the bar size</li>
</ul>

<?=$this->extraExamples?>
