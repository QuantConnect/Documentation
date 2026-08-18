<? include(DOCS_RESOURCES."/landing-page-introductions/individual-brokerages.php"); ?>

<p>The Bloomberg&trade; FIX connection routes your orders directly to the Bloomberg&trade; network over the <a href='/docs/v2/cloud-platform/live-trading/brokerages/fix-connections'>Financial Information eXchange (FIX)</a> protocol. It's an order routing connection, so it doesn't supply market data. Bloomberg&trade; FIX is in no way affiliated with or endorsed by Bloomberg&trade;; it is simply an add-on.</p>

<p>This connection is separate from <a href='/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-emsx'>Terminal Link</a>, which reaches the Bloomberg&trade; Execution Management System (EMSX) through the Bloomberg&trade; Server API (SAPI). The FIX connection talks to the Bloomberg&trade; FixNet HUB instead of the SAPI, so none of the SAPI setup applies. Both connections support the same asset classes, but they differ in the order types they accept, whether they accept order updates, and the account types they support.</p>

<p>To use the Bloomberg&trade; FIX connection, you need to be a member of an organization on the Institution <a href='https://www.quantconnect.com/docs/v2/cloud-platform/organizations/tier-features'>tier</a>.</p>

<p>To view how we model this connection, see the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/bloomberg-fix'>Bloomberg FIX brokerage model documentation</a>.</p>
