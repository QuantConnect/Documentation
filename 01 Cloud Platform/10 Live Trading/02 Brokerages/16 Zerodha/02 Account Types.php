<p>Zerodha supports cash and margin accounts.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/set-brokerage-model/zerodha.html"); ?>

<p>Zerodha only supports trading Indian Rupees.</p>
<div class="section-example-container">
    <pre class="csharp">SetAccountCurrency("INR");</pre>
    <pre class="python">self.SetAccountCurrency("INR")</pre>
</div>


<h4>Create an Account</h4>
<p>To create a Zerodha account, follow the <a rel="nofollow" target="_blank" href="https://signup.zerodha.com/">account creation wizard</a> on the Zerodha website.</p>

<p>You will need API credentials to deploy live algorithms with your brokerage account. After you open your Zerodha account, follow these steps to get your API credentials:</p>
<ol>
    <li><a href="https://developers.kite.trade/signup" rel="nofollow" target="_blank">Create a Kite Connect developer account</a>.</li>
    <li>On the My apps page, click <span class='button-name'>Create new app</span>.</li>
    <li>On the Create a new app page, fill in the form.</li>
    <p>For the <span class='field-name'>Redirect URL</span> field, if you don't have a redirect URL, use https://zerodha.com.</p>
    <li>Click <span class='button-name'>Create</span>.</li>
    <li>Copy and save your API key and API secret.</li>
    <li>In a terminal, run the following Python script:
	    <div class="section-example-container">
			<pre class="python">from kiteconnect import KiteConnect
import webbrowser

api_key = input ("Enter your API key: ")
kite = KiteConnect(api_key=api_key)
​webbrowser.open(kite.login_url())
request_token = input ("Enter your request_token: ")
api_secret = input ("Enter your API secret: ")
data = kite.generate_session(request_token, api_secret=api_secret)
kite.set_access_token(data["access_token"])
print(f"Your access_token is {data['access_token']}")</pre>
		</div>
		<p>Input the data that the script requests. When the script opens your redirect URL page, log in and then copy the request token that's in the URL parameters.</p>
		<p>When the script prints your access token. Copy and save it somewhere safe.</p>
	</li>
</ol>

// login page opens automatically, need to log in
// the request token is in the url parameters

<p>For more information about the process, see the <a rel="nofollow" target="_blank" href="https://kite.trade/docs/connect/v3/user/#login-flow">Login flow</a> in the Kite Connect documentation.</p>

<h4>Paper Trading</h4>
<p>Zerodha doesn't support paper trading.</p>