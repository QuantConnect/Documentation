<p>The<code class="csharp">DeploymentDetails</code><code class="python">deployment_details</code> property of your algorithm is a read-only dictionary of the details. LEAN populates it before it calls the <code class="csharp">Initialize</code><code class="python">initialize</code> method, so you can read it anywhere in your algorithm.</p>

<div class="section-example-container">
    <pre class="csharp">var deploymentDetails = DeploymentDetails.Select(kvp => $"{kvp.Key}:{kvp.Value}");
Log($"deploymentDetails: {string.Join(", ", deploymentDetails)}");</pre>
    <pre class="python">deployment_details = [f'{kvp.key}:{kvp.value}' for kvp in self.deployment_details]
self.log(f'deployment_details: {deployment_details}')</pre>
</div>

<p>The <a href='/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm/live-algorithm-statistics'>/live/read</a> API endpoint also returns the details in its <code>deploymentDetails</code> field, so you can read them outside of the algorithm.</p>
