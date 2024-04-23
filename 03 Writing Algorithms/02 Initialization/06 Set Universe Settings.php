<p>The universe settings of your algorithm configure some properties of the universe constituents. The following table describes the properties of the <code>UniverseSettings</code> object:</p>

<table class='qc-table table vertical-table'>
    <tbody>
    <tr>
            <td>
                <h4>Property: <span><code class="csharp">Asynchronous</code><code class="python">asynchronous</code></span></h4>
                <p class='property-description'>Should the universe selection run asynchronously to boost speed?</p>
                <p>Data Type: <span><code>bool</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code class='csharp'>false</code><code class='python'>False</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">ExtendedMarketHours</code><code class="python">extended_market_hours</code></span></h4>
                <p class='property-description'>Should assets also feed extended market hours? You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
                <p>Data Type: <span><code>bool</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code class='csharp'>false</code><code class='python'>False</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">FillForward</code><code class="python">fill_forward</code></span></h4>
                <p class='property-description'>Should asset data fill forward?</p>
                <p>Data Type: <span><code>bool</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code class='csharp'>true</code><code class='python'>True</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">MinimumTimeInUniverse</code><code class="python">minimum_time_in_universe</code></span></h4>
                <p class='property-description'>What's the minimum time assets should be in the universe?</p>
                <p>Data Type: <span><code class='csharp'>TimeSpan</code><code class='python'>timedelta</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code class='csharp'>TimeSpan.FromDays(1)</code><code class='python'>timedelta(1)</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">Resolution</code><code class="python">resolution</code></span></h4>
                <p class='property-description'>What resolution should assets use?</p>
                <p>Data Type: <span><code>Resolution</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">ContractDepthOffset</code><code class="python">contract_depth_offset</code></span></h4>
                <p class='property-description'>What offset from the current front month should be used for <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Future contracts</a>? 0 uses the front month and 1 uses the back month contract. This setting is only available for Future assets.</p>
                <p>Data Type: <span><code>int</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code>0</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">DataMappingMode</code><code class="python">data_mapping_mode</code></span></h4>
                <p class='property-description'>How should continuous Future contracts be mapped? This setting is only available for Future assets.</p>
                <p>Data Type: <span><code>DataMappingMode</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code>DataMappingMode.OpenInterest</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">DataNormalizationMode</code><code class="pythpn">data_normalization_mode</code></span></h4>
                <p class='property-description'>How should historical prices be adjusted? This setting is only available for Equity and Futures assets.</p>
                <p>Data Type: <span><code>DataNormalizationMode</code></span><span class='pipe-separator'>  |  </span> Default Value: <span><code>DataNormalizationMode.Adjusted</code></span></p>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Property: <span><code class="csharp">Leverage</code><code class="python">leverage</code></span></h4>
                <p class='property-description'>What leverage should assets use in the universe? This setting is not available for derivative assets.</p>
                <p>Data Type: <span><code class='csharp'>decimal</code><code class='python'>float</code></span><span  class='pipe-separator'>  |  </span> Default Value: <span><code>Security.NullLeverage</code></span></p>
            </td>
        </tr>
</tbody>
</table>

<p>To set the <code class="csharp">UniverseSettings</code><code class="python">universe_settings</code>, update the preceding properties in the <code class="csharp">Initialize</code><code class="python">initialize</code> method before you add the universe. These settings are globals, so they apply to all universes you create.</p>
 
<div class="section-example-container">
<pre class="csharp">// Request second resolution data. This will be slow!
UniverseSettings.Resolution = Resolution.Second;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
<pre class="python"># Request second resolution data. This will be slow!
self.universe_settings.resolution = Resolution.SECOND
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>For more information about universe settings, see the related documentation for <a href='/docs/v2/writing-algorithms/universes/settings'>classic</a> and <a href='/docs/v2/writing-algorithms/algorithm-framework/universe-selection/universe-settings'>framework</a> algorithms.</p>