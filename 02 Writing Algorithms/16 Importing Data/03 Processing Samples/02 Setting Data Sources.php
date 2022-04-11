<p>
	In your custom data class, the $[GetSource,M:QuantConnect.Data.BaseData.Reader] method instructs LEAN where to find your data. It must return a $[SubscriptionDataSource,T:QuantConnect.Data.SubscriptionDataSource] object
	containing the location to find your data, and the format of the data (SubscriptionTransportMedium). You can even change source locations for backtesting and live modes. We support many different data types and formats. <br></p>

<h4>Local Files</h4>
<p>
To retreive data from your local disk, use $[SubscriptionTransportMedium.LocalFile,T:QuantConnect.SubscriptionTransportMedium].
</p>

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
<p>
To download data from a remote URL like GitHub and Dropbox, use $[SubscriptionTransportMedium.RemoteFile,T:QuantConnect.SubscriptionTransportMedium].
</p>

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

<h4>REST API</h4>
<p>
To retrieve data from a REST endpoint, use $[SubscriptionTransportMedium.Rest,T:QuantConnect.SubscriptionTransportMedium]. The endpoint is polled at each <code>Resolution</code> time step that you set when you initialize the data subscription. This is generally intended for live data sources.
</p>

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
