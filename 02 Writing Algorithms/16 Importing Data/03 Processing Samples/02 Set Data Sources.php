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
    def GetSource(self, config, date, isLive):
        return SubscriptionDataSource("&lt;filePath&gt;", SubscriptionTransportMedium.LocalFile)</pre>
</div>

## Import example of local path from Key Concepts

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
    def GetSource(self, config, date, isLive):
        return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.RemoteFile)</pre>
</div>

## Import examples of github and dropbox urls from Key Concepts

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
    def GetSource(self, config, date, isLive):
        return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.Rest)</pre>
</div>
