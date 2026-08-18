<? include(DOCS_RESOURCES."/landing-page-introductions/individual-brokerages.php"); ?>

<img class='docs-image' style='width: 50%' src='https://cdn.quantconnect.com/i/tu/terminal-link-logo.svg' alt="Terminal link icon">

<p>QuantConnect offers two ways to route orders to the Bloomberg&trade; network. Neither of them is affiliated with or endorsed by Bloomberg&trade;; they are simply add-ons.</p>

<ul>
    <li><span class='page-section-name'>Terminal Link</span> integrates with the Bloomberg&trade; Server API (SAPI) or Desktop API (DAPI) and routes orders through the Bloomberg&trade; Execution Management System (EMSX). Add Terminal Link to your organization to access the 1,300+ prime brokerages in the EMSX network.</li>
    <li><span class='page-section-name'>Bloomberg&trade; FIX</span> connects straight to the Bloomberg&trade; FixNet HUB over the <a href='/docs/v2/cloud-platform/live-trading/brokerages/fix-connections'>Financial Information eXchange (FIX)</a> protocol. It doesn't use the SAPI, so none of the SAPI setup applies.</li>
</ul>

<p>Both connections only route orders, so neither of them supplies market data. QuantConnect Cloud supports the Bloomberg&trade; Server API rather than the Desktop API, so with Terminal Link you can route orders to any of the prime brokerages that Bloomberg&trade; supports while you leverage the data, server management, and data management from QuantConnect, giving you the best of both worlds. To use Terminal Link, you need to be a member of an organization on the Trading Firm or Institution <a href='https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features'>tier</a>.</p>

<p>To view how we model these connections, see the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/bloomberg'>Bloomberg brokerage model documentation</a>.</p>
