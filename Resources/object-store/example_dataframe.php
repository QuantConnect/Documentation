<? $cs = $research ? "qb." : ""; $py = $research ? "qb." : "self."; ?>
<p>Follow these steps to create a DataFrame, save it into the Object Store, and load it from the Object Store:</p>

<ol>
    <li>Get some historical data.</li>
    <div class='section-example-container'>
    <pre class='csharp'>
var spy = <?=$cs?>AddEquity("SPY").Symbol;
var history = <?=$cs?>History(<?=$cs?>Securities.Keys, 360, Resolution.Daily);</pre>
    <pre class='python'>
spy = <?=$py?>AddEquity("SPY").Symbol
df = <?=$py?>History(<?=$py?>Securities.Keys, 360, Resolution.Daily)</pre>
    </div>

    <li class='csharp'>Create a DataFrame.</li>
    <div class='csharp section-example-container'>
    <pre class='csharp'>using Microsoft.Data.Analysis; // 

var columns = new DataFrameColumn[] {
    new DateTimeDataFrameColumn("Time", history.Select(x => (DateTime)x[spy].EndTime)),
    new DecimalDataFrameColumn("SPY Open", history.Select(x => (decimal)x[spy].Open)),
    new DecimalDataFrameColumn("SPY High", history.Select(x => (decimal)x[spy].High)),
    new DecimalDataFrameColumn("SPY Low", history.Select(x => (decimal)x[spy].Low)),
    new DecimalDataFrameColumn("SPY Close", history.Select(x => (decimal)x[spy].Close))
};
var df = new DataFrame(columns);</pre>
    </div>

    <li>Get the file path for a specific key in the Object Store.</li>
    <div class='section-example-container'>
    <pre class='csharp'>var filePath = <?=$cs?>ObjectStore.GetFilePath("df_to_csv");</pre>
    <pre class='python'>file_path = <?=$py?>ObjectStore.GetFilePath("df_to_csv")</pre>
    </div>

    <li>Save the DataFrame as <span class='public-file-name'>CSV</span> with the <a class='csharp' rel="nofollow" target="_blank" href="https://learn.microsoft.com/en-us/dotnet/api/microsoft.data.analysis.dataframe.savecsv">SaveCsv</a><a class='python' rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_csv.html">to_csv</a> method.</li>
    <div class='section-example-container'>
    <pre class='csharp'>DataFrame.SaveCsv(df, filePath);    // File size: 26520 bytes</pre>
    <pre class='python'>df.to_csv(file_path)   # File size: 32721 bytes</pre>
    </div>

    <li>Load the DataFrame with the <a class='csharp' rel="nofollow" target="_blank" href="https://learn.microsoft.com/en-us/dotnet/api/microsoft.data.analysis.dataframe.loadcsv">LoadCsv</a><a class='python' rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.read_csv.html">read_csv</a> method.</li>
    <div class='section-example-container'>
    <pre class='csharp'>var reread = DataFrame.LoadCsv(filePath);</pre>
    <pre class='python'>reread = pd.read_csv(file_path)</pre>
    </div>
</ol>

<p class='python'><code>pandas</code> supports saving and loading data frames in the following additional formats:</p>

<ul class='python'>
    <li><a rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_xml.html">XML</a>.</li>
    <div class='section-example-container'>
    <pre class='python'>file_path = <?=$py?>ObjectStore.GetFilePath("df_to_xml")
df.to_xml(file_path)   # File size: 87816 bytes
reread = pd.read_xml(file_path)</pre>
    </div> 
    <li><a rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_json.html">JSON</a>.</li>
    <div class='section-example-container'>
    <pre class='python'>file_path = <?=$py?>ObjectStore.GetFilePath("df_to_json")
df.to_json(file_path)   # File size: 125250 bytes
reread = pd.read_json(file_path)</pre>
    </div> 
    <li><a rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_parquet.html">Parquet</a>.</li>
    <div class='section-example-container'>
    <pre class='python'>file_path = <?=$py?>ObjectStore.GetFilePath("df_to_parquet")
df.to_parquet(file_path)   # File size: 23996 bytes
reread = pd.read_parquet(file_path)</pre>
    </div>
    <li><a rel="nofollow" target="_blank" href="https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_pickle.html">Pickle</a>.</li>
    <div class='section-example-container'>
    <pre class='python'>file_path = <?=$py?>ObjectStore.GetFilePath("df_to_pickle")
df.to_pickle(file_path)   # File size: 19868 bytes
reread = pd.read_pickle(file_path)</pre>
    </div>
</ul>
