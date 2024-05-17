<p>The <code class="csharp">ActiveSecurities</code><code class="python">active_securities</code> property of the algorithm class contains all of the assets currently in your algorithm. It is a dictionary where the key is a <code>Symbol</code> and the value is a <code>Security</code>. When you remove an asset from a universe, LEAN usually removes the security from the <code class="csharp">ActiveSecurities</code><code class="python">active_securities</code> collection and removes the security subscription. However, it won't remove the security in any of the following situations:</p>
<ul>
    <li>You own the security.</li>
    <li>You have an open order for the security.</li>
    <li>The security wasn't in the universe long enough to meet the <code class="csharp">MinimumTimeInUniverse</code><code class="python">minimum_time_in_universe</code> setting.</li>
</ul>

<p>When LEAN removes the security, the <code>Security</code> object remains in the <code class="csharp">Securities</code><code class="python">securities</code> collection for record-keeping purposes, like tracking fees and trading volume.</p>

<? echo file_get_contents(DOCS_RESOURCES."/securities/securities_total.html"); ?>

<p>To get only the assets that are currently in the universe, see <a href='/docs/v2/writing-algorithms/universes/key-concepts#11-Selected-Securities'>Selected Securities</a>.</p>