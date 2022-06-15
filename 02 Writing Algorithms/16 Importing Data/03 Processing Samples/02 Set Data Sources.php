<p>The <code>GetSource</code> method in your custom data class instructs LEAN where to find the data. This method must return a <code>SubscriptionDataSource</code> object, which contains the data location and format (<code>SubscriptionTransportMedium</code>). You can even change source locations for backtesting and live modes. We support many different data sources.</p>

<h4>Local Files</h4>
<p>To retrieve data from your local disk, use <code>SubscriptionTransportMedium.LocalFile</code>.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCustomDataType : BaseData
{
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLive)
    {
          return new SubscriptionDataSource("&lt;filePath&gt;", SubscriptionTransportMedium.LocalFile);
    }
}</pre>
<pre class="python">class MyCustomDataType(PythonData):
    def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
        return SubscriptionDataSource("&lt;filePath&gt;", SubscriptionTransportMedium.LocalFile)</pre>
</div>

<p>The <code>&lt;filePath&gt;</code> is relative to the project root. For example, if you have a structure like <span class="private-file-name">&lt;projectName&gt;/custom-data/file.csv</span>, use <code>"custom-data/file.csv"</code>.</p>

<h4>Remote Files</h4>
<p>To download data from a remote URL like GitHub and Dropbox, use <code>SubscriptionTransportMedium.RemoteFile</code>.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCustomDataType : BaseData
{
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLive)
    {
          return new SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.RemoteFile);
    }
}</pre>
<pre class="python">class MyCustomDataType(PythonData):
    def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
        return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.RemoteFile)</pre>
</div>

<p> To access remote files, add <code>?dl=1</code> to the end of the file URL. This parameter lets you download the direct file link, not the HTML page of the file.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>

<h4>REST APIs</h4>
<p>To retrieve data from a REST endpoint, use <code>SubscriptionTransportMedium.Rest</code>. LEAN polls the endpoint at each <code>Resolution</code> time step that you set when you initialize the data subscription. This <code>SubscriptionTransportMedium</code> is generally for live data sources.</p>

<div class="section-example-container">
<pre class="csharp">public class MyCustomDataType : BaseData
{
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLive)
    {
          return new SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.Rest);
    }
}</pre>
<pre class="python">class MyCustomDataType(PythonData):
    def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLive: bool) -&gt; SubscriptionDataSource:
        return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.Rest)</pre>
</div>