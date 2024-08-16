<p>By default, LEAN subscribes to the Option contracts that have the following characteristics:</p>

<? $annotation = "exclude weeklys"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To adjust the universe of contracts, set a filter. The filter usually runs at the first bar of every day. When the filter selects a contract that isn't currently in your universe, LEAN adds the new contract data to the next <code>Slice</code> that it passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method.</p>
