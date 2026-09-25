<p>The bar-building process can exclude ticks. If a tick is excluded, its volume is aggregated in the bar but its price is not aggregated in the bar. If no tick in a period sets the price, the period has no bar. Ticks are excluded if any of the following statements are true:</p>
<ul>
    <li>The tick is suspicious.</li>
    <li>The trade is from the FINRA exchange and FINRA filtering is active for the security. For more information about FINRA filtering, see the paragraph after this list.</li>
    <li>The trade has none of the included <code>TradeConditionFlags</code> and at least one of the excluded <code>TradeConditionFlags</code> in the following table.</li>
    <li>The bid size and the ask size of the quote are both less than 100 shares.</li>
    <li>The quote has none of the included <code>QuoteConditionFlags</code> in the following table.</li>
    <li>The quote has at least one of the excluded <code>QuoteConditionFlags</code> in the following table.</li>
</ul>

<p>FINRA trades include internal crosses from brokers, dealers, and dark pools. At the start of each trading day, FINRA filtering is inactive for every security. FINRA filtering becomes active once both of the following conditions are met:</p>
<ul>
    <li>The first tick of the day that passes the condition flag rules in the preceding list has a price of at least $5. We check the price only once per day, so if this tick is below $5, FINRA filtering stays inactive for the rest of the day.</li>
    <li>The dollar volume of the trades that pass the condition flag rules reaches $5,000,000. The count starts with the first trade of the day, including pre-market trades.</li>
</ul>
<p>After FINRA filtering becomes active, FINRA trades don't set the price of the bar for the rest of the day. Their volume is still aggregated in the bar. Before FINRA filtering becomes active, a FINRA trade sets the price of the bar if it passes the condition flag rules.</p>

<p>The following table shows the <code>TradeConditionFlags</code> that the bar-building process uses:</p>
<? include(DOCS_RESOURCES."/data-feeds/trade-condition-flags-table.html"); ?>

<p>The following table shows the included <code>QuoteConditionFlags</code>:</p>
<? include(DOCS_RESOURCES."/data-feeds/quote-condition-flags-included-table.html"); ?>

<p>The following table shows the excluded <code>QuoteConditionFlags</code>:</p>
<? include(DOCS_RESOURCES."/data-feeds/quote-condition-flags-excluded-table.html"); ?>

<p>In the preceding tables, <span class='new-term'>Participant</span> refers to the entities on page 19 of the <a class='document-title' rel='nofollow' target='_blank' href='https://www.ctaplan.com/publicdocs/ctaplan/notifications/trader-update/CTS_BINARY_OUTPUT_SPECIFICATION.pdf'>Consolidated Tape System Multicast Output Binary Specification</a>.</p>
