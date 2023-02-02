<p>To get the account details of an organization, call the <code>ReadAccount</code> with the organization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var account = api.ReadAccount(organizationId);</pre>
    <pre class="python">account = api.ReadAccount(organization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-organization-id-in-research.html"); ?>

<p>If you don't provide an organization Id, the method uses the Id of your <a href='/docs/v2/cloud-platform/organizations/administration#07-View-All-Organizations'>default organization</a>.</p>

<p>The <code>ReadAccount</code> method returns an <code>Account</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.Account"></div>
