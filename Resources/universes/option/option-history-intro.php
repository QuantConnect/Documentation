<p>
    The <a href="<?=$filterLink?>">contract filter</a> determines which <?=$underlyingAssetClass?> Option contracts are in your universe each trading day.
    The default filter selects the contracts with the following characteristics:
</p>
<? $annotation = "exclude weeklys"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To change the filter, call the <code class="csharp">SetFilter</code><code class="python">set_filter</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">// Set the contract filter to select contracts that have the strike price 
// within 1 strike level and expire within 90 days.
option.SetFilter(-1, 1, 0, 90);</pre>
    <pre class="python"># Set the contract filter to select contracts that have the strike price 
# within 1 strike level and expire within 90 days.
option.set_filter(-1, 1, 0, 90)</pre>
</div>

<p>To get the prices and volumes for all of the <?=$underlyingAssetClass?> Option contracts that pass your filter during a specific period of time, call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the underlying <?=$underlyingAssetClass?> <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>option_history = qb.option_history(
    <?=strtolower($underlyingAssetClass)?>_symbol, datetime(2024, 1, 1), datetime(2024, 1, 5), Resolution.MINUTE, 
    fill_forward=False, extended_market_hours=False
)</pre>
    <pre class='csharp'>var optionHistory = qb.OptionHistory(
    <?=strtolower($underlyingAssetClass)?>Symbol, new DateTime(2024, 1, 1), new DateTime(2024, 1, 5), Resolution.Minute,
    fillForward: false, extendedMarketHours: false
);</pre>
</div>
