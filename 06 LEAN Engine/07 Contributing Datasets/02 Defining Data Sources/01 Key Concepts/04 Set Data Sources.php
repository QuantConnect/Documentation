<p>The <code>GetSource</code> method in your dataset class instructs LEAN where to find the data. This method must return a <code>SubscriptionDataSource</code> object, which contains the data location and format.</p>

<p>The following table describes the arguments the <code>SubscriptionDataSource</code> accepts:</p>

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
            <td><code>string</code></td>
            <td>Data source location. The path should be completely lowercase unless absolutely required. Don't use special characters in your output path, except <code>-</code> in directories names and <code>_</code> in file names. Your output file(s) must be in CSV format.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>transportMedium</code></td>
            <td><code>SubscriptionTransportMedium</code></td>
            <td>The transport medium to be used to retrieve data from the source.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>format</code></td>
            <td><code>FileFormat</code></td>
            <td>The format of the data within the source.</td>
            <td><code>FileFormat.Csv</code></td>
        </tr>
        <tr>
            <td><code>headers</code></td>
            <td><code>IEnumerable&lt;KeyValuePair&lt;string, string&gt;&gt;</code></td>
            <td>The headers to be used for this source. In cloud algorithms, each of the key-value pairs can consist of up to 1,000 characters.</td>
            <td><code>null</code></td>
        </tr>
    </tbody>
</table>

<p>QuantConnect hosts your data, so the <code>transportMedium</code> must be <code>SubscriptionTransportMedium.LocalFile</code> and the <code>format</code> must be <code>FileFormat.Csv</code>.</p>

<p>The following table describes the arguments the <code>GetSource</code> method accepts:</p>

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
            <td><code>DateTime</code></td>
            <td>Date of this source file</td>
        </tr>
        <tr>
            <td><code>isLiveMode</code></td>
            <td><code>bool</code></td>
            <td><code>true</code> if algorithm is running in live mode</td>
        </tr>
    </tbody>
</table>

<p>You can use these arguments to create <code>SubscriptionDataSource</code> objects representing different locations and formats.</p>

<div class="section-example-container">
    <pre>public class VendorNameDatasetName : BaseData
{
    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config,
        DateTime date,
        bool isLive)
    {
        // File location example:
        // data/alternative/&lt;vendorName&gt;/&lt;datasetName&gt;/aapl.csv
        return new SubscriptionDataSource(
            Path.Combine(
                Globals.DataFolder,
                "alternative",
                "&lt;vendorName&gt;",
                "&lt;datasetName&gt;",
                $"{config.Symbol.Value.ToLowerInvariant()}.csv"),
            SubscriptionTransportMedium.LocalFile,
            FileFormat.Csv);
    }
}</pre>
</div>
