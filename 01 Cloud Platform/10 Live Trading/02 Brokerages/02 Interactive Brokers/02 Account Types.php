<p>The <a rel='nofollow' target='_blank' href='https://qnt.co/interactivebrokers'>IB</a> API does not support the IBKR LITE plan. You need an IBKR PRO plan. Individual and Financial Advisor (FA) accounts are available.</p>

<h4>Individual Accounts</h4>
<p>IB supports cash and margin accounts. To set the account type in an algorithm, see the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/interactive-brokers'>IB brokerage model documentation</a>.</p>

<h4>FA Accounts</h4>
<p>IB supports FA accounts for Trading Firm and Institution organizations. FA accounts enable certified professionals to use a single trading algorithm to manage several client accounts. If your account code starts with F, FA, or I, then you have an FA account. For more information about FA accounts, see <a href="/docs/v2/writing-algorithms/trading-and-orders/financial-advisors">Financial Advisors</a>.</p>

<h4>Create an Account</h4>
<p>You need to open an IBKR Pro account to deploy algorithms with IB. The IB API does not support IBKR Lite accounts. To create an IB account, see the <a rel="nofollow" target="_blank" href="https://gdcdyn.interactivebrokers.com/en/index.php?f=4695">Open an Account</a> page on the IB website.</p>

<p>You need to activate IBKR Mobile Authentication (IB Key) to deploy live algorithms with your brokerage account. After you open your account, follow the <a rel="nofollow" target="_blank" href="https://ibkr.info/node/2260">installation and activation instructions</a> on the IB website.</p>

<h4>Paper Trading</h4>
<p>IB supports paper trading. Follow the <a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/software/ptgstl/topics/papertrader.htm">Opening a Paper Trading Account</a> page in the IB documentation to set up your paper trading account.</p>

<? include(DOCS_RESOURCES."/data-feeds/ib-share-data-with-paper-trading.html"); ?>

<p>The IB paper trading environment simulates most aspects of a production Trader Workstation account, but you may encounter some differences due to its construction as a simulator with no execution or clearing abilities.</p>

<h4>Insured Bank Deposit Sweep Program</h4>
<p>
    LEAN doesn't support IB accounts in the Insured Bank Deposit Sweep Program because when LEAN reads your account balances, it includes cash that's in the FDIC Sweep Account Cash, which isn't tradable. 
    For example, if your account has $150K USD of cash, only $100K may be available to trade if $50K is in FDIC Sweep Account Cash.
    To opt-out the program, see <a href='https://www.ibkrguides.com/clientportal/insured-bank-deposit-sweep-program.htm' rel='nofollow' target='_blank'>Insured Bank Deposit Sweep Program</a>.
</p>

<h4>Dividend Election</h4>
<p>
    <a href='https://ibkrguides.com/kb/overview-of-drip.htm' rel='nofollow' target='_blank'>Dividend election</a> is an option where you can elect how you wish to receive your dividends for stocks and mutual funds. You must turn automatic dividend election off to receive them in cash. Reinvestment can change the quantity of shares you own to fractional shares, and LEAN doesn't support <a href='https://www.interactivebrokers.com/en/trading/fractional-trading.php' rel='nofollow' target='_blank'>fractional trading</a>. For example, if your account has 1270.8604 shares of TLT after dividend reinvestment, you cannot liquidate the position.
</p>
