<? include(DOCS_RESOURCES."/cli/init/auth-intro.html"); ?>
<p>Run <code>lean login</code> to open an interactive wizard which asks you for your user Id and API token.
<a href="https://www.quantconnect.com/docs/v2/cloud-platform/community/profile#09-Request-API-Token">Request these credentials</a> and we'll email them to you.</p>

<div class="cli section-example-container">
<pre>$ lean login
Your user Id and API token are needed to make authenticated requests to the QuantConnect API
You can request these credentials on https://www.quantconnect.com/account
Both will be saved in /home/&lt;username&gt;/.lean/credentials
User id: &lt;user id&gt;
API token: &lt;api token&gt;
Successfully logged in</pre>
</div>