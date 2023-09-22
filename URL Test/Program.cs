/*
 * QUANTCONNECT.COM - Democratizing Finance, Empowering Individuals.
 * Lean Algorithmic Trading Engine v2.0. Copyright 2014 QuantConnect Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

using QuantConnect;
using QuantConnect.Logging;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Threading;
using System.Threading.Tasks;

const string path = "..";
const string root = "https://www.quantconnect.com/";
const string leanIo = "https://www.lean.io/";
var leanIoFolder = new string[] {"05 Lean CLI", "06 LEAN Engine"};
var edgeCaseUrls = new []
{
    "https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates"
};

var stopwatch = Stopwatch.StartNew();
var i = 1;
var tasks = new List<Task>();
var errorFlag = false;

var urlFiles = GetAllUrls();
var count = urlFiles.Count;
Log.Trace($"Start Testing {count} URLs...");

foreach (var (url, files) in urlFiles)
{
    try
    {
        tasks.Add(
            HttpRequester(url, files).ContinueWith(response =>
            {
                var content = response.Result;

                if (content.Contains("400 Bad Request") || content.Contains("403 Unauthorized") ||
                    content.Contains("404 Not found"))
                {
                    Log.Error(content);
                    errorFlag = true;
                }
                else if (content.Contains("Sorry we couldn't find that page."))
                {
                    Log.Error($"404 Not found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                    errorFlag = true;
                }

                // Check "go to section" mapping is wrong
                if (url.Contains('#') && url.Contains("/docs/v2") && !url.Contains("api-reference") &&
                    !edgeCaseUrls.Contains(url))
                {
                    var expected = url.Split("docs/v2/").Last()
                        .Replace('/', Path.DirectorySeparatorChar)
                        .Replace('-', ' ')
                        .Replace('#', Path.DirectorySeparatorChar)
                        .Replace("Look ahead", "Look-ahead") // special case
                        .Replace("look ahead", "look-ahead") // special case
                        .Replace("profit loss", "profit-loss") // special case
                        .Replace("out of the money", "out-of-the-money") // special case
                        .Replace("Built in", "Built-in") // special case
                        .Replace("scikit learn", "scikit-learn") // special case
                        .Replace("third party", "third-party") // special case
                        .Replace("C and Visual Studio", "C# and Visual Studio") // special case
                        .Replace("C and VS Code", "C# and VS Code") // special case
                        .Replace("C and Rider", "C# and Rider") // special case
                        .Replace("C and Rider", "C# and Rider") // special case
                        .Replace("mixed mode consolidators", "mixed-mode consolidators") // special case
                        .Replace("Multi Alpha", "Multi-Alpha") // special case
                        .Replace("Margin3F", "Margin%3F") // special case
                        .Replace("Volatility3F", "Volatility%3F") // special case
                        .ToLower();

                    var section = url.Split('#').Last()
                        .Replace('-', ' ')
                        .Replace("Look ahead", "Look-ahead") // special case
                        .Replace("look ahead", "look-ahead") // special case
                        .Replace("profit loss", "profit-loss") // special case
                        .Replace("out of the money", "out-of-the-money") // special case
                        .Replace("Built in", "Built-in") // special case
                        .Replace("C and Visual Studio", "C# and Visual Studio") // special case
                        .Replace("C and VS Code", "C# and VS Code") // special case
                        .Replace("C and Rider", "C# and Rider") // special case
                        .Replace("Multi Alpha", "Multi-Alpha") // special case
                        .Replace("Margin3F", "Margin%3F") // special case
                        .Replace("Volatility3F", "Volatility%3F"); // special case
                    var allFiles = Directory.GetFiles(path, $"{section}.*", SearchOption.AllDirectories);
                    var noEquals = allFiles.All(dir =>
                    {
                        var subPaths = dir.Split(Path.DirectorySeparatorChar)
                            .Where(x => int.TryParse(x.AsSpan(0, 1), out _));
                        var nonNumberedPath = string.Join(Path.DirectorySeparatorChar,
                            subPaths.SkipLast(1).Select(x =>
                                new string(x.Where(c => c < '0' || c > '9').ToArray()).Trim()));
                        var sectionPath = subPaths.Last().Split('.').First();

                        return $"{nonNumberedPath}{Path.DirectorySeparatorChar}{sectionPath}".ToLower() != expected;
                    });

                    if (noEquals || allFiles.Length == 0)
                    {
                        Log.Error(
                            $"No Section \"{section}\" was found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                        errorFlag = true;
                    }
                }
            })
        );
    }
    catch (Exception e)
    {
        Log.Error(e, $":\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
    }

    if (i % 100 == 0)
    {
        Log.Trace($"\tDone {i}/{count} ({Convert.ToDouble(i) / count:P2})");
    }

    Interlocked.Increment(ref i);

    if (tasks.Count == 10)
    {
        Task.WaitAll(tasks.ToArray());
        tasks.Clear();
    }
}

if (tasks.Count > 0)
{
    Task.WaitAll(tasks.ToArray());
    tasks.Clear();
}

Log.Trace($"Finished in {stopwatch.Elapsed.ToStringInvariant(null)}");
if (errorFlag)
{
    throw new Exception("There is/are broken link(s) in the docs! Refer to the above logs for reference.");
}

Dictionary<string, List<string>> GetAllUrls()
{
    Dictionary<string, List<string>> urlFiles = new();

    var allFiles = Directory.GetFiles(path, "*.*", SearchOption.AllDirectories)
        // Exclude Documentation Updates since it may include broken links
        // Exclude single-page docs since it is generated from basic docs
        .Where(x => !x.Contains(".git") && !x.Contains("single-page") &&
                    !x.EndsWith("Documentation Updates.html"))
        .OrderBy(x => x);

    foreach (var file in allFiles)
    {
        foreach (var line in File.ReadAllLines(file))
        {
            if (!line.Contains("a href")) continue;

            var hrefs = line.Replace('\'', '\"').Split("a href=\"").Skip(1);
            foreach (var href in hrefs)
            {
                var url = href.Split('\"').First();
                var subUrl = String.Empty;

                if (string.IsNullOrWhiteSpace(url) || url.Contains('{') || url.Contains('}')) continue;

                if (!url.Contains("http"))
                {
                    if (url[0] == '#')
                    {
                        subUrl = string.Join("/",
                                file.Split(Path.DirectorySeparatorChar).SkipLast(1)
                                    .Select(x => x.Remove(0, 2).Trim()))
                            .ToLower().Replace(" ", "-");
                        url = $"{root}docs/v2{subUrl}{url}";
                    }
                    else if (url.Contains("mailto:"))
                    {
                    }
                    else if (url[0] != '/')
                    {
                        subUrl = string.Join("/",
                                file.Split(Path.DirectorySeparatorChar).SkipLast(2)
                                    .Select(x => x.Remove(0, 2).Trim()))
                            .ToLower().Replace(" ", "-");
                        url = $"{root}docs/v2{subUrl}/{url}";
                    }
                    else
                    {
                        url = $"{root}{url.Remove(0, 1)}";
                    }
                }

                if (url.Contains("sources")) continue;

                if (!urlFiles.ContainsKey(url))
                {
                    urlFiles.Add(url, new List<string>());
                }

                urlFiles[url].Add(file);

                if (leanIoFolder.Any(file.Contains))
                {
                    url = url.Replace(root, leanIo);

                    if (!urlFiles.ContainsKey(url))
                    {
                        urlFiles.Add(url, new List<string>());
                    }

                    urlFiles[url].Add(file);
                }
            }
        }
    }

    return urlFiles;
}

async Task<string> HttpRequester(string url, List<string> files)
{
    try
    {
        using var client = new HttpClient();
        var response = await client.GetAsync(url);
        var statusCode = response.StatusCode;

        switch (statusCode)
        {
            case HttpStatusCode.BadRequest:
                return $"400 Bad Request:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
            case HttpStatusCode.Unauthorized:
                return $"403 Unauthorized:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
            case HttpStatusCode.NotFound:
                return $"404 Not found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
        }

        response.EnsureSuccessStatusCode();
        var content = await response.Content.ReadAsStringAsync();
        response.Dispose();

        return content;
    }
    catch
    {
        Thread.Sleep(1000);
    }

    return $"Fail to request:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
}