<p>To add a custom runtime statistic, call the <code>SetRuntimeStatistic</code> method with a <code>name</code> and <code>value</code>. The <code>value</code> argument can be a <code>string</code> or a number.</p>

<div class="section-example-container">
    <pre class="csharp">SetRuntimeStatistic(name, value);</pre>
    <pre class="python">self.SetRuntimeStatistic(name, value)</pre>
</div>

<p>Don't try to overwrite any of the preceding default runtime statistics. LEAN overwrites the value that you try to set.</p>

<? if ($writingAlgorithms) { ?>
<p>To get the value of a custom runtime statistic, index the <code>RuntimeStatistics</code> member of the algorithm class with the statistic name. The values of the <code>RuntimeStatistics</code> dictionary are strings, so you may need to cast the result to a different data type.</p>

<div class="section-example-container">
    <pre class="csharp">var value = RuntimeStatistics[name];</pre>
    <pre class="python">value = self.RuntimeStatistic[name]</pre>
</div>
<? } ?>
    
