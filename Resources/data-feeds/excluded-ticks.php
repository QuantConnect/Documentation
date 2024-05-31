<p>The bar-building process can exclude ticks. If a tick is excluded, its volume is aggregated in the bar but its price is not aggregated in the bar. Ticks are excluded if any of the following statements are true:</p>
<ul>
    <li>The tick is suspicious.</li>
    <li>The tick is from the FINRA exchange and meets our price and volume thresholds.</li>
    <li>The trade has none of the following included <code>TradeConditionFlags</code> and at least one of the following excluded <code>TradeConditionFlags</code>:</li>
    <? echo file_get_contents(DOCS_RESOURCES."/data-feeds/trade-condition-flags-table.html"); ?>
    <li>The quote has a size of less than 100 shares.</li>
    <li>The quote has none of the following included <code>QuoteConditionFlags</code> and at least one of the following excluded <code>QuoteConditionFlags</code>:</li>
    <li>The quote has one of the following <code>QuoteConditionFlags</code>:</li>
    <? echo file_get_contents(DOCS_RESOURCES."/data-feeds/quote-condition-flags-table.html"); ?>
</ul>

<p>In the preceding tables, <span class='new-term'>Participant</span> refers to the entities on page 19 of the <a class='document-title' rel='nofollow' target='_blank' href='https://www.ctaplan.com/publicdocs/ctaplan/notifications/trader-update/CTS_BINARY_OUTPUT_SPECIFICATION.pdf'>Consolidated Tape System Multicast Output Binary Specification</a>.