<p>To compile a project, call the <code>CreateCompile</code> method with the project ID.</p>
<div class="section-example-container">
    <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using QuantConnect;
using QuantConnect.Api;

var compile = api.CreateCompile(projectID);</pre>
    <pre class="python">compile = api.CreateCompile(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>CreateCompile</code> method returns a <code>Compile</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Compile'></div>
