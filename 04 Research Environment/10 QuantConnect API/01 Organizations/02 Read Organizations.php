<p>To get the details of an organization, call the <code>ReadOrganization</code> with the organization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var organization = api.ReadOrganization(organizationId);</pre>
    <pre class="python">organization = api.ReadOrganization(organization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-organization-id-in-research.html"); ?>

<p>The <code>ReadOrganization</code> method returns an <code>Organization</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.Organization"></div>
