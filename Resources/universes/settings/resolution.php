<p>The <code class="csharp">Resolution</code><code class="python">resolution</code> setting defines the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/periods'>time period</a> of the asset data. The <code>Resolution</code> enumeration has the following members:</p>
<div data-tree='QuantConnect.Resolution'></div>

<p>To view which resolutions are available for the asset class of your universe, follow these steps:</p>

<ol>
	<li>Open the <a href='/docs/v2/writing-algorithms/securities/asset-classes'>Asset Classes</a> documentation page.</li>
	<li>Click an asset class.</li>
	<li>Click <span class='button-name'>Requesting Data</span>.</li>
	<li>On the Requesting Data page, in the table of contents, click <span class='button-name'>Resolutions</span>.</li>
</ol>

<p>The default value is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, 
