<p>Create custom charts and they will display on the results page of your algorithm. We support the following types of plots:</p>
<div class="section-example-container">
	<pre class="all prettyprint prettyprinted" style=""><span class="pln">   </span><span class="typ">SeriesType</span><span class="pun">.</span><span class="typ">Line</span><span class="pln">
             </span><span class="pun">.</span><span class="typ">Scatter</span><span class="pln">
             </span><span class="pun">.</span><span class="typ">Candle</span><span class="pln">
             </span><span class="pun">.</span><span class="typ">Bar</span><span class="pln">
             </span><span class="pun">.</span><span class="typ">Flag</span></pre>
</div>


<p>When you create scatter plots, you can set a marker symbol. We support the following marker symbols:
	</p>
<div class="section-example-container">
	<pre class="all prettyprint prettyprinted" style=""><span class="pln">   </span><span class="typ">ScatterMarkerSymbol</span><span class="pun">.</span><span class="typ">Circle</span><span class="pln">
                      </span><span class="pun">.</span><span class="typ">Diamond</span><span class="pln">
                      </span><span class="pun">.</span><span class="typ">Square</span><span class="pln">
                      </span><span class="pun">.</span><span class="typ">Triangle</span><span class="pln">
                      </span><span class="pun">.</span><span class="typ">TriangleDown</span></pre>
</div>


<p>Custom charts are limited to 4,000 data points. Intensive charting requires hundreds of megabytes of data, which is too much to stream online or display in a web browser. If you exceed the limit, the following message displays in the Console:</p>

<span class='error-messages'>Exceeded maximum points per chart, data skipped</span>

<p>The candlestick plots that the charting API supports are automatically generated through a stream of values. It's not currently possible to create custom candlestick plots on the results page with your own open, high, low, and close values, but we hope to add the functionality in the future. For the time being, we recommend that you save the plot data in the ObjectStore and then load it into the Research Environment where you can create candlestick charts with <a href="/docs/v2/research-environment/tutorials/plotting-in-research">third-party charting packages</a>.</p>
