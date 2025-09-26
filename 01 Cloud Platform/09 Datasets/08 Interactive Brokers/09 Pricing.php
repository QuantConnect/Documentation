<p>To use IB data in your algorithms, <a rel="nofollow" target="_blank" href="https://www.ibkrguides.com/clientportal/usersettings/marketdatasubscriptions.htm">subscribe to IB market data</a>. We support all of the IB data subscriptions that are related to <a href="/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#04-Asset-Classes">the securities and markets we support</a>. Members usually subscribe to the following IB market data:</p>

<ul>
    <li>US Securities Snapshot and Futures Value Bundle</li>
    <li>US Equity and Options Add-On Streaming Bundle</li>
    <li>CFE Enhanced Top of Book (L1 for VIX Futures)</li>
    <li>CME S&P Indexes (L1 for SPX and NDX)</li>
    <li>CBOE Streaming Market Indexes (L1 for VIX Index)</li>
</ul>

<p>The IB data provider can stream data for up to 100 assets by default, but IB may let you stream more than 100 assets based on your commissions and equity value. For more information about quotas from IB, see the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/pricing/research-news-marketdata.php'>Market Data Pricing Overview</a> page on the IB website. If your algorithm adds more than the your quota, LEAN logs an error message from IB. To increase the quota, purchase a Quote Booster from IB.</p>

<p>To see the latest prices, check the <a rel='nofollow' target='_blank' href='https://www.interactivebrokers.com/en/index.php?f=14193'>Market Data Pricing Overview</a> page on the IB website. IB can take up to 24 hours to process subscription requests. So after you subscribe to data, you need to wait 24 hours before you can use it in your algorithms. When you subscribe to data, IB only assigns your data subscription to one of your accounts. If you want to assign the subscription to a different account, then contact IB.</p>

<? include(DOCS_RESOURCES."/data-feeds/ib-share-data-with-paper-trading.html"); ?>

