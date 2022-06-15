<p>You can import data from local files, remote files, or REST endpoints.</p>

<h4>Local Files</h4>
<p>To access local files in your algorithms, you must run local algorithms with the <a href="/docs/v2/lean-cli/key-concepts/getting-started">CLI</a>. The file path is relative to the project root. For example, if you save the file at <span class="private-file-name">&lt;projectName&gt;/custom-data/file.csv</span>, use the following snippets:</p>

<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python"># Import Technique 1: Bulk downloads
data = self.Download("custom-data/file.csv")

# Import Technique 2: Processing samples
def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
    return SubscriptionDataSource("custom-data/file.csv", SubscriptionTransportMedium.LocalFile)</pre>
</div>


<h4>Remote Files</h4>
<p>The most common remote file providers to use are GitHub and Dropbox.</p>

<h5>GitHub</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python"># Import Technique 1: Bulk downloads
data = self.Download("https://raw.githubusercontent.com/&lt;organization&gt;/&lt;repo&gt;/&lt;path&gt;")

# Import Technique 2: Processing samples
def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
    return SubscriptionDataSource("https://raw.githubusercontent.com/&lt;organization&gt;/&lt;repo&gt;/&lt;path&gt;", SubscriptionTransportMedium.RemoteFile)</pre>
</div>

<h5>Dropbox</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>

<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python"># Import Technique 1: Bulk downloads
data = self.Download("https://www.dropbox.com/&lt;filePath&gt;?dl=1")

# Import Technique 2: Processing samples
def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
    return SubscriptionDataSource("https://www.dropbox.com/&lt;filePath&gt;?dl=1", SubscriptionTransportMedium.RemoteFile)</pre>
</div>

<h4>REST Endpoints</h4>
<p>To access the data from REST endpoints, pass the endpoint URL to the <code>Download</code> method or the <code>SubscriptionDataSource</code> constructor.</p>

<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python"># Import Technique 1: Bulk downloads
data = self.Download("&lt;sourceURL&gt;")

# Import Technique 2: Processing samples
def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
    return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.Rest)</pre>
</div>
