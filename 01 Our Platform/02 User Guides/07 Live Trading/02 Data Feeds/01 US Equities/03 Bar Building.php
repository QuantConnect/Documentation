<?php echo file_get_contents(DOCS_RESOURCES."/data-feeds/bar-building.html"); ?>

<h4>Discrepancies</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/live-and-backtest-differences.html"); ?>

<h4>Opening and Closing Auctions</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/opening-and-closing-auctions.html"); ?>

<h4>Excluded Ticks</h4>
<p>The bar building process can exclude ticks. If a tick is excluded, its volume is aggregated in the bar but its price is not aggregated in the bar. Ticks are excluded if any of the the following statements are true:</p>
<ul>
    <li>The tick is suspicious.</li>
    <li>The tick is from the FINRA exchange and meets our price and volume thresholds.</li>
<p>FINRA is a government-authorized not-for-profit organization that oversees U.S. broker-dealers. The ticks that have FINRA as the exchange represent trades like internal crosses from brokers, dealers and dark pools. We can miss a complete day of aggregated trades in low volume securities because the flag filtering is quite strict.</p>
    <li>The tick is a trade, the tick has none of the included <code>TradeConditionFlags</code>, and the tick has at least one of the excluded <code>TradeConditionFlags</code>.</li>
    <p>The following table describes the included and excluded <code>TradeConditionFlags</code>:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th style='width: 25%'><code>TradeConditionFlags</code></th>
                <th>Status</th>
                <th>Description</th>
            </tr>
       </thead>
       <tbody>
            <tr>
                <td><code>Regular</code></td>
                <td>Included</td>
                <td>A trade made without stated conditions is deemed the regular way for settlement on the third business day following the transaction date.</td>
            </tr>
            <tr>
                <td><code>FormT</code></td>
                <td>Included</td>
                <td>Trading in extended hours enables investors to react quickly to events that typically occur outside regular market hours, such as earnings reports. However, liquidity may be constrained during such Form T trading, resulting in wide bid-ask spreads.</td>
            </tr>
            <tr>
                <td><code>Cash</code></td>
                <td>Included</td>
                <td>A transaction that requires delivery of securities and payment on the same day the trade takes place.</td>
            </tr>
            <tr>
                <td><code>ExtendedHours</code></td>
                <td>Included</td>
                <td>Identifies a trade that was executed outside of regular primary market hours and is reported as an extended hours trade.</td>
            </tr>
            <tr>
                <td><code>NextDay</code></td>
                <td>Included</td>
                <td>A transaction that requires the delivery of securities on the first business day following the trade date.</td>
            </tr>
            <tr>
                <td><code>OfficialClose</code></td>
                <td>Included</td>
                <td>Indicates the "official" closing value determined by a Market Center. This transaction report will contain the market center generated closing price.</td>
            </tr>
            <tr>
                <td><code>OfficialOpen</code></td>
                <td>Included</td>
                <td>Indicates the 'Official' open value as determined by a Market Center. This transaction report will contain the market center generated opening price.</td>
            </tr>
            <tr>
                <td><code>ClosingPrints</code></td>
                <td>Included</td>
                <td>The transaction that constituted the trade-through was a single priced closing transaction by the Market Center.</td>
            </tr>
            <tr>
                <td><code>OpeningPrints</code></td>
                <td>Included</td>
                <td>The trade that constituted the trade-through was a single priced opening transaction by the Market Center.</td>
            </tr>
            <tr>
                <td><code>IntermarketSweep</code></td>
                <td>Excluded</td>
                <td>The transaction that constituted the trade-through was the execution of an order identified as an Intermarket Sweep Order.</td>
            </tr>
            <tr>
                <td><code>TradeThroughExempt</code></td>
                <td>Excluded</td>
                <td>Denotes whether or not a trade is exempt (Rule 611).</td>
            </tr>
            <tr>
                <td><code>OddLot</code></td>
                <td>Excluded</td>
                <td>Denotes the trade is an odd lot less than a 100 shares.</td>
            </tr>
       </tbody>
    </table>
    <li>The tick is a quote with less than 100 shares.</li>
    <li>The tick is a quote and has one of the following <code>QuoteConditionFlags</code>:</li>
    <table class="qc-table table">
        <thead>
            <tr>
                <th style='width: 25%'><code>QuoteConditionFlags</code></th>
                <th>Description</th>
            </tr>
       </thead>
       <tbody>
            <tr>
                <td><code>Closing</code></td>
                <td>Indicates that this quote was the last quote for a security for that Participant.</td>
            </tr>
            <tr>
                <td><code>NewsDissemination</code></td>
                <td>Denotes a regulatory trading halt when relevant news influencing the security is being disseminated. Trading is 
 suspended until the primary market determines that an adequate publication or disclosure of information has occurred.</td>
            </tr>
            <tr>
                <td><code>NewsPending</code></td>
                <td>Denotes a regulatory Trading Halt due to an expected news announcement, which may influence the security. An Opening Delay or Trading Halt may be continued once the news has been disseminated.</td>
            </tr>
            <tr>
                <td><code>TradingRangeIndication</code></td>
                <td>Denotes the probable trading range (Bid and Offer prices, no sizes) of a security that is not Opening Delayed or Trading Halted. The Trading Range Indication is used prior to or after the opening of a security.</td>
            </tr>
            <tr>
                <td><code>OrderImbalance</code></td>
                <td>Denotes a non-regulatory halt condition where there is a significant imbalance of buy or sell orders.</td>
            </tr>
            <tr>
                <td><code>Resume</code></td>
                <td>Indicates that trading for a Participant is no longer suspended in a security that had been Opening Delayed or Trading Halted.</td>
            </tr>
       </tbody>
    </table>

    <li>The tick is a quote and doesn't have any of the following <code>QuoteConditionFlags</code>:</li>
    <table class="qc-table table">
        <thead>
            <tr>
                <th style='width: 25%'><code>QuoteConditionFlags</code></th>
                <th>Description</th>
            </tr>
       </thead>
       <tbody>
            <tr>
                <td><code>Regular</code></td>
                <td>This condition is used for the majority of quotes to indicate a normal trading environment.</td>
            </tr>
            <tr>
                <td><code>Slow</code></td>
                <td>This condition is used to indicate that the quote is a Slow Quote on both the bid and offer sides due to a Set Slow List that includes high price securities.</td>
            </tr>
            <tr>
                <td><code>Gap</code></td>
                <td>While in this mode, auto-execution is not eligible, the quote is then considered manual and non-firm in the bid and offer, and either or both sides can be traded through as per Regulation NMS.</td>
            </tr>
            <tr>
                <td><code>OpeningQuote</code></td>
                <td>This condition can be disseminated to indicate that this quote was the opening quote for a security for that Participant.</td>
            </tr>
            <tr>
                <td><code>FastTrading</code></td>
                <td>For extremely active periods of short duration. While in this mode, the UTP Participant will enter quotations on a best efforts basis.</td>
            </tr>
            <tr>
                <td><code>Resume</code></td>
                <td>Indicate that trading for a Participant is no longer suspended in a security which had been Opening Delayed or Trading Halted.</td>
            </tr>
       </tbody>
    </table>
</ul>

<p>In the preceding tables, <span class='new-term'>Participant</span> refers to the entities on page 19 of the <a class='document-title' rel='nofollow' target='_blank' href='https://www.ctaplan.com/publicdocs/ctaplan/notifications/trader-update/CTS_BINARY_OUTPUT_SPECIFICATION.pdf'>Consolidated Tape System Multicast Output Binary Specification</a>.
