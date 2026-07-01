<p>After you subscribe to local access (see <a href="/docs/v2/lean-cli/datasets/quantconnect/us-equity-coarse-fundamental#02-Prerequisites">Prerequisites</a>), open a terminal in your <a href="https://www.quantconnect.com/docs/v2/lean-cli/initialization/organization-workspaces">organization workspace</a> and run the following commands to bulk download the data and its prerequisites.</p>

<p>To download or update the US Equity Coarse Universe data, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Coarse Universe" --data-type "Bulk" --overwrite</pre> 
</div>

<p>To download or update your local copy of the US Equity Security Master, run:</p>
<div class="cli section-example-container">
     <pre>$ lean data download --dataset "US Equity Security Master"</pre> 
</div>