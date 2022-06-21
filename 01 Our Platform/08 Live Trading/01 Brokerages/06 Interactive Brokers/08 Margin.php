<p>We model buying power and margin calls to ensure your algorithm stays within the margin requirements.</p>

<h4>Buying Power</h4>
<p>In the US, IB allows up to 2x leverage on Equity trades for margin accounts. In other countries, IB may offer different amounts of leverage. To figure out how much leverage you can access, check with your local legislation or contact an IB representative. We model the US version of IB leverage by default.</p>

<?php include(DOCS_RESOURCES."/brokerages/margin-calls.html"); ?>

<?php include(DOCS_RESOURCES."/brokerages/pattern-day-trader-rule.html"); ?>

<p>If you have less than $25,000 in your account and you try to open a 4th day trade for an Equity asset in a 5 business day period, you'll get the following error message:</p>

<div class='error-messages'>Message: 201 - Order rejected - reason:Potential Pattern Day Trade. A potential pattern day trader error message means that an account has less than the SEC required USD 25,000 minimum Net Liquidation Value AND the number of available day trades (3) has already been used within the last 5 days.. You need to maintain an account balance of at least USD 25,000 if you wish to day trade. If you do not, we restrict you to no more than 3 day trades within any 5 business day period as a 4th trade would create a violation.. This order rejection serves to prevent you from opening a 4th trade and possibly closing it today. Please refer to our <a href="https://www.ibkr.info/article/193">Knowledge Base</a> for further details.</div>