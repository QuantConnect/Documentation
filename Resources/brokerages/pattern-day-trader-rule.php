<? if ($includeh4) { ?><h4>Pattern Day Trading</h4><? } ?>

<p>If all of the following statements are true, you are classified as a pattern day trader:</p>
<ul>
    <li>You reside in the United States.</li>
    <li>You trade in a margin account.</li>
    <li>You execute 4+ intraday US Equity trades within 5 business days.</li>
    <li>Your intraday US Equity trades represent more than 6% of your total trades.</li>
</ul>

<p>Pattern day traders must maintain a minimum equity of $25,000 in their margin account to continue trading. For more information about pattern day trading, see <a rel='nofollow' target='_blank' href='https://www.finra.org/investors/learn-to-invest/advanced-investing/day-trading-margin-requirements-know-rules'>Am I a Pattern Day Trader?</a> on the FINRA website.</p>

<p>The <code>PatternDayTradingMarginModel</code> doesn't enforce minimum equity rules and doesn't limit your trades, but it adjusts your available leverage based on the market state. During regular market hours, you can use up to 4x leverage. During extended market hours, you can use up to 2x leverage.</p>

<div class='section-example-container'>
    <pre class='csharp'>security.MarginModel = new PatternDayTradingMarginModel();</pre>
    <pre class='python'>security.margin_model = PatternDayTradingMarginModel()</pre>
</div>