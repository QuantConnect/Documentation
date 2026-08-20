<p>You can change your organization to any of the paid tiers or the Free tier.</p>
<h4>Paid Tiers</h4>
<p>Follow these steps to change to a paid organization tier:</p>

<ol>
    <li>In the left navigation bar, click <a href="/organization" class="menu-name">Organization &gt; Home</a>.</li>
    <li>On the organization homepage, click <span class='button-name'>Edit Plan</span>.</li>
    <li>Click the <span class='tab-name'>Choose a Plan</span> tab.</li>
    <li>Click <span class='button-name'>CHOOSE TIER</span> under the organization tier you want.</li>
    <li>Select a tier pack.</li>

    <p>The following table describes the type of packs we have available:</p>

    <table class="qc-table table">
        <thead>
            <tr>
                <th>Pack Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Suggested Packs</td>
                <td>Packs with pre-selected team seats, support seats, and nodes.</td>
            </tr>
            <tr>
                <td>Build Your Own Pack</td>
                <td>Packs with custom selections for team seats, support seats, add-ons, and market subscriptions.</td>
            </tr>
        </tbody>
    </table>

    <li>Select monthly or annual billing.<br></li>
    <li><span class='qualifier'>(Optional)</span> Click <span class='button-name'>+ Add Coupon</span> and then enter your coupon code.<br></li>
    <li>If your organization doesn't have a credit card added, click <span class='button-name'>Proceed to Checkout</span> and then enter your credit card details.</li>
    <li>Click <span class='button-name'>Update Subscriptions</span> or <span class='button-name'>Subscribe Now</span>.</li>
</ol>


<h4>Free Tier</h4>

<div class="highlight">
    <p>Downgrading to the Free tier deletes data. If your goal is to lower your bill instead of leaving the platform, downgrade to the <a href="/docs/v2/cloud-platform/organizations/tier-features#03-Quant-Researcher-Tier">Quant Researcher</a> tier and remove every subscription except the seat. It's the lowest-cost paid tier, so your projects, backtest results, logs, and <a href="/docs/v2/cloud-platform/object-store">Object Store</a> data are preserved. If you only need a short break, <a href="/docs/v2/cloud-platform/organizations/billing#08-Pause-and-Resume-Subscriptions">pause your subscriptions</a> instead, which keeps your organization as it is.</p>
</div>

<p>Follow these steps to downgrade to the Free tier:</p>

<ol>
    <li>On the <a href="/downgrade" class="menu-name">Downgrade</a> page, click <span class='button-name'>Cancel My Plan</span>.</li>
    <li>Read the list of consequences the page shows and confirm you accept all of them. Cancelling your subscription means the following:

        <ul>
            <li>You lose access to node scaling and return to shared micro instances.</li>
            <li>You can no longer collaborate with others.</li>
            <li>Your backtest results and logs are deleted.</li>
            <li>Projects that exceed the Free tier limits are deleted.</li>
            <li>Your existing Object Store data is deleted.</li>
            <li>Your live algorithms are stopped and their logs are deleted.</li>
        </ul>

        <p>These deletions are permanent, so save what you want to keep before you continue:</p>

        <ul>
            <li>To keep your projects, install the <a href="/docs/v2/lean-cli">LEAN CLI</a> and run <a href="/docs/v2/lean-cli/api-reference/lean-cloud-pull"><code>lean cloud pull</code></a> to copy them to your local drive.</li>
            <li>To keep your results, call the <a href="/docs/v2/cloud-platform/api-reference/backtest-management/read-backtest">Read Backtest</a> and <a href="/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm">Read Live Algorithm</a> endpoints and store the responses.</li>
            <li>You can't export your Object Store data. <a href="/docs/v2/lean-cli/object-store#04-Download-Files">Downloading files from the Object Store</a> is only available to permissioned <a href="/docs/v2/cloud-platform/organizations/tier-features#06-Institution-Tier">Institution</a> tier organizations, so unless your organization has that permission, treat the deletion as final.</li>
        </ul>
    </li>
    <li>Click the <span class='button-name'>I understand that my subscription will be terminated at the end of the current billing cycle</span> check box and then click <span class='button-name'>Cancel My Subscription</span>.</li>
</ol>
