<p>The <code class="csharp">GetSource</code><code class="python">get_source</code> method in your custom data class instructs LEAN where to find the data.</p>
<div class="section-example-container">
    <pre class="csharp">public class MyCustomDataType : BaseData
{
    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config,
        DateTime date,
        bool isLiveMode)
    {
        if (isLiveMode)
        {
            return new SubscriptionDataSource("https://www.bitstamp.net/api/ticker/", SubscriptionTransportMedium.Rest);
        }

        var source = $"http://my-ftp-server.com/{config.Symbol.Value}/{date:yyyyMMdd}.csv";
        return new SubscriptionDataSource(source, SubscriptionTransportMedium.RemoteFile);

        /*
        // Example of loading from the Object Store:
        return new SubscriptionDataSource(Bitstamp.KEY, 
            SubscriptionTransportMedium.ObjectStore);

        // Example of loading a remote zip file:
        return new SubscriptionDataSource(
            "https://cdn.quantconnect.com/uploads/multi_csv_zipped_file.zip",
            SubscriptionTransportMedium.RemoteFile,
            FileFormat.ZipEntryName);

        // Example of loading a remote zip file and accessing a CSV file inside it:
        return new SubscriptionDataSource(
            "https://cdn.quantconnect.com/uploads/multi_csv_zipped_file.zip#csv_file_10.csv",
            SubscriptionTransportMedium.RemoteFile,
            FileFormat.ZipEntryName);
        */
    }
}</pre>
    <pre class="python">class MyCustomDataType(PythonData):
    def get_source(self,
         config: SubscriptionDataConfig,
         date: datetime,
         is_live_mode: bool) -&gt; SubscriptionDataSource:
        
         if is_live_mode:
            return SubscriptionDataSource("https://www.bitstamp.net/api/ticker/", SubscriptionTransportMedium.REST)

        source = f"http://my-ftp-server.com/{config.symbol.value}/{date:%Y%M%d}.csv"
        return SubscriptionDataSource(source, SubscriptionTransportMedium.REMOTE_FILE)

        # Example of loading from the Object Store:
        # return SubscriptionDataSource(Bitstamp.KEY, SubscriptionTransportMedium.OBJECT_STORE)

        # Example of loading a remote zip file:
        # return SubscriptionDataSource(
        #     "https://cdn.quantconnect.com/uploads/multi_csv_zipped_file.zip",
        #     SubscriptionTransportMedium.REMOTE_FILE,
        #     FileFormat.ZIP_ENTRY_NAME
        # )

        # Example of loading a remote zip file and accessing a CSV file inside it:
        # return SubscriptionDataSource(
        #     "https://cdn.quantconnect.com/uploads/multi_csv_zipped_file.zip#csv_file_10.csv",
        #     SubscriptionTransportMedium.REMOTE_FILE,
        #     FileFormat.ZIP_ENTRY_NAME
        # )
</pre>
</div>

<p>The following table describes the arguments the <code class="csharp">GetSource</code><code class="python">get_source</code> method accepts:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>config</code></td>
            <td><code>SubscriptionDataConfig</code></td>
            <td>The subscription configuration</td>
        </tr>
        <tr>
            <td><code>date</code></td>
            <td><code class="csharp">DateTime</code><code class="python">datetime</code></td>
            <td>Date of this source file</td>
        </tr>
        <tr>
            <td><code class="csharp">isLiveMode</code><code class="python">is_live_mode</code></td>
            <td><code>bool</code></td>
            <td><code class="csharp">true</code><code class="python">True</code> if algorithm is running in live mode</td>
        </tr>
    </tbody>
</table>

<p>You can use these arguments to create <code>SubscriptionDataSource</code> objects representing different locations and formats. The following table describes the arguments the <code>SubscriptionDataSource</code> accepts:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>source</code></td>
            <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>Data source location</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">transportMedium</code><code class="python">transport_medium</code></td>
            <td><code>SubscriptionTransportMedium</code></td>
            <td>The transport medium to be used to retrieve data from the source</td>
            <td></td>
        </tr>
        <tr>
            <td><code>format</code></td>
            <td><code>FileFormat</code></td>
            <td>The format of the data within the source</td>
            <td><code>FileFormat.Csv</code></td>
        </tr>
        <tr>
            <td><code>headers</code></td>
            <td><code>IEnumerable&lt;KeyValuePair&lt;string, string&gt;&gt;</code></td>
            <td>The headers to be used for this source. In cloud algorithms, each of the key-value pairs can consist of up to 1,000 characters.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>The <code>FileFormat</code> enumeration has the following members:</p>

<div data-tree="QuantConnect.Data.FileFormat"></div>

<p>The <code>SubscriptionTransportMedium</code> enumeration has the following members:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Member</th>
            <th>Description</th>
            <th>Example</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class='csharp'>LocalFile</code><code class='python'>LOCAL_FILE</code></td>
            <td>The data comes from disk</td>
            <td><a href='https://github.com/QuantConnect/Lean.DataSource.CBOE/blob/master/CBOE.cs#L60'>Lean.DataSource.CBOE</a></td>
        </tr>
        <tr>
            <td><code class='csharp'>RemoteFile</code><code class='python'>REMOTE_FILE</code></td>
            <td>The data is downloaded from a remote source</td>
            <td><code><a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/csv-format-example#99-Demonstration-Algorithms'>Custom Securities Examples</a></td>
        </tr>
        <tr>
            <td><code class='csharp'>Rest</code><code class='python'>REST</code></td>
            <td>The data comes from a rest call that is polled and returns a single line/data point of information</td>
            <td><code class="csharp">LiveMode</code><code class="python">live_mode</code> case of <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts#99-Demonstration-Algorithm'>Demonstration Algorithm</a></td>
        </tr>
        <tr>
            <td><code class='csharp'>ObjectStore</code><code class='python'>OBJECT_STORE</code></td>
            <td>The data comes from the object store</td>
            <td><a href='/docs/v2/writing-algorithms/object-store#13-Example-of-Custom-Data'>Example of Custom Data</a></td>
        </tr>
    </tbody>
</table>
