<p>Downloading files from the Object Store is only available to <a href="/docs/v2/cloud-platform/organizations/tier-features#06-Institution-Tier">Institution</a> tier organizations that have signed QuantConnect's derived data agreement. It is not available on the Free, Quant Researcher, Team, or Trading Firm tiers, and <a href="/docs/v2/cloud-platform/object-store#07-Edit-Storage-Plan">adding storage</a> doesn't change that.</p>

<p>The restriction applies to every file in the Object Store, including files you upload and files your algorithms generate. The Object Store is a single shared store, so the platform can't distinguish your own files from data derived from licensed datasets, and there is no per-file or per-organization exception. Keeping the data in the cloud is what lets QuantConnect offer licensed data at a fraction of its direct cost. Even permissioned organizations can only download derived data such as machine learning models and signal files. Raw price and alternative data can never be downloaded.</p>

<p>If your organization isn't permissioned, the <span class='button-name'>Download</span> action on the Object Store page is unavailable, and the <a href="/docs/v2/cloud-platform/api-reference/object-store-management/get-object-store-file">API</a> and <a href="/docs/v2/lean-cli/object-store#04-Download-Files">CLI</a> return <code>Organization does not have derivative export enabled</code>. Use the data in the cloud instead:</p>

<ul>
    <li>Read the file in the <a href="/docs/v2/research-environment/object-store#05-Read-Data">Research Environment</a> or in a <a href="/docs/v2/writing-algorithms/object-store#05-Read-Data">backtest</a> and do the analysis there. For a full walkthrough, see <a href='/docs/v2/writing-algorithms/object-store#13-Example-for-Logging'>Example for Logging</a>.</li>
    <li>For values a backtest produces, plot them and use <a href="/docs/v2/cloud-platform/backtesting/results#17-Download-Results">Download Results</a>, or log them within your <a href="/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas">log quota</a>.</li>
    <li>Project source files don't need the Object Store. Pull them with <a href="/docs/v2/lean-cli/projects/cloud-synchronization#02-Pulling-Cloud-Projects">lean cloud pull</a>.</li>
</ul>

<p>If you represent a fund or company and are interested in the Institution tier but unsure if it's right for you, <a href="/appointments">contact us</a> to schedule a demo with QuantConnect to learn more.</p>
