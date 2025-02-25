<p>You can download the following information <?=$localPlatform ? "for cloud backtests" : ""?> from the backtest results page:</p>

<ul>
    <li>Runtime statistics</li>
    <li>Charts</li>
    <li>The data in the <span class='tab-name'>Overview</span> tab</li>
    <li>The data in the <span class='tab-name'>Orders</span> tab</li>
</ul>

<p>To download the preceding information, open the backtest results page, click the <span class='tab-name'>Overview</span> tab, and then click <span class='button-name'>Download Results</span>. The timestamps in the file are based in Coordinated Universal Time (UTC).</p>

<p>The information is stored in <code>JSON</code> format. The following code converts the JSON result file into <code>CSV</code> format.</p>

<div class="section-example-container">
    <pre class="csharp">using System;
using System.Collections.Generic;
using System.Data;
using System.IO;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;

static DataTable CreateMultiIndexDataTable(JObject jsonObject)
{
    DataTable dataTable = new DataTable();
    dataTable.Columns.Add("Key Path");
    dataTable.Columns.Add("Value");

    // Flatten the JSON and populate the DataTable.
    FlattenJson(jsonObject, dataTable, string.Empty);
    
    return dataTable;
}

static void FlattenJson(JToken json, DataTable dataTable, string parentKey)
{
    foreach (var property in json.Children&lt;JProperty&gt;())
    {
        string currentKey = string.IsNullOrEmpty(parentKey) ? property.Name : $"{parentKey}.{property.Name}";

        if (property.Value.Type == JTokenType.Object)
        {
            FlattenJson(property.Value, dataTable, currentKey);
        }
        else if (property.Value.Type == JTokenType.Array)
        {
            for (int i = 0; i &lt; property.Value.Count(); i++)
            {
                FlattenJson(property.Value[i], dataTable, $"{currentKey}[{i}]");
            }
        }
        else
        {
            dataTable.Rows.Add(currentKey, property.Value.ToString());
        }
    }
}

static void WriteDataTableToCsv(DataTable dataTable, string filePath)
{
    using (StreamWriter writer = new StreamWriter(filePath))
    {
        // Write header.
        writer.WriteLine(string.Join(",", dataTable.Columns));

        // Write rows.
        foreach (DataRow row in dataTable.Rows)
        {
            writer.WriteLine(string.Join(",", row.ItemArray));
        }
    }
}

// Load the JSON file.
string jsonData = File.ReadAllText("&lt;YOUR_JSON_FILE&gt;.json");
var jsonObject = JsonConvert.DeserializeObject&lt;JObject&gt;(jsonData);

// Create a DataTable to hold the multi-index structure.
DataTable dataTable = CreateMultiIndexDataTable(jsonObject);

// Save to CSV.
WriteDataTableToCsv(dataTable, "results.csv");</pre>
    <pre class="python">import json
import pandas as pd

# Load the JSON file.
data = json.loads(open("&lt;YOUR_JSON_FILE&gt;.json", "r", encoding="utf-8").read())

# Recursive function to flatten the JSON and create tuples for multi-index.
def flatten_json(obj, parent_key='', sep='_'):
    items = []
    for k, v in obj.items():
        new_key = f"{parent_key}{sep}{k}" if parent_key else k
        if isinstance(v, dict):
            items.extend(flatten_json(v, new_key, sep=sep).items())
        else:
            items.append((new_key, v))
    return dict(items)

# Flatten the JSON data.
flat_data = flatten_json(data)

# Split keys into a list for multi-index.
multi_index_tuples = [tuple(k.split('_')) for k in flat_data.keys()]
values = list(flat_data.values())

# Create the multi-index DataFrame.
multi_index = pd.MultiIndex.from_tuples(multi_index_tuples)
df = pd.DataFrame(values, index=multi_index, columns=['Value'])

# Save to CSV.
df.to_csv('results.csv')</pre>
</div>