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

using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Threading;
using System.Threading.Tasks;
using QuantConnect.Logging;

namespace QuantConnect.Tests
{
    /// <summary>
    /// Entrypoint for the url tester
    /// </summary>
    public class Program
    {
        private static readonly string _path = "..";
        private static readonly string _root = "https://www.quantconnect.com/";
        private static Dictionary<string, List<string>> _urlFiles = new();
        private static bool _errorFlag = false;
        private static readonly string[] _edgeCaseUrls = new string[] {
            "https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates"
        };

        /// <summary>
        /// URL tester program
        /// </summary>
        public static void Main()
        {
            GetAllUrls();
            TestUrls();
            
            // raise an exception to fail the workflow if any broken links there
            if (_errorFlag)
            {
                throw new Exception("There is/are broken link(s) in the docs! Refer to the above logs for reference.");
            }
        }

        /// <summary>
        /// Get all urls from all files in a root dir
        /// </summary>
        /// <return>A dictionary with url as key and list of files containing the url as values</return>
        private static void GetAllUrls()
        {
            var allFiles = Directory.GetFiles(_path, "*.*", SearchOption.AllDirectories)
                    // Exclude Documentation Updates since it may include broken links
                    // Exclude single-page docs since it is generated from basic docs
                    .Where(x => !x.EndsWith("Documentation Updates.html") && !x.Contains("single-page")).ToList();

            foreach(var file in allFiles)
            {
                foreach(var line in File.ReadAllLines(file))
                {
                    if (line.Contains("a href"))
                    {
                        var hrefs = line.Replace('\'', '\"').Split("a href=\"").Skip(1);
                        foreach(var href in hrefs)
                        {
                            var url = href.Split('\"').First();

                            if (string.IsNullOrWhiteSpace(url) || url.Contains('{') || url.Contains('}')) continue;

                            if (!url.Contains("http"))
                            {
                                if (url[0] == '#')
                                {
                                    var subUrl = string.Join("/", file.Split(Path.DirectorySeparatorChar).SkipLast(1).Select(x => x.Remove(0, 2).Trim()))
                                                 .ToLower().Replace(" ", "-");
                                    url = $"{_root}docs/v2{subUrl}{url}";
                                }
                                else if (url.Contains("mailto:"))
                                {
                                }
                                else if (url[0] != '/')
                                {
                                    var subUrl = string.Join("/", file.Split(Path.DirectorySeparatorChar).SkipLast(2).Select(x => x.Remove(0, 2).Trim()))
                                                 .ToLower().Replace(" ", "-");
                                    url = $"{_root}docs/v2{subUrl}/{url}";
                                }
                                else
                                {
                                    url = $"{_root}{url.Remove(0, 1)}";
                                }
                            }

                            if (url.Contains("sources")) continue;

                            if (!_urlFiles.ContainsKey(url))
                            {
                                _urlFiles.Add(url, new List<string>());
                            }

                            _urlFiles[url].Add(file);
                        }
                    }
                }
            }
        }

        /// <summary>
        /// Test all urls if successfully get
        /// </summary>
        private static void TestUrls()
        {
            var stopwatch = Stopwatch.StartNew();
            Log.Trace($"Start Testing URLs...");

            var i = 1;
            var count = _urlFiles.Count;
            var tasks = new List<Task>();

            foreach (var (url, files) in _urlFiles)
            {
                try
                {        
                    tasks.Add(
                        HttpRequester(url, files).ContinueWith(response => {
                            var content = response.Result;
                            
                            if (content.Contains("400 Bad Request") || content.Contains("403 Unauthorized") || content.Contains("404 Not found"))
                            {
                                Log.Error(content);
                                _errorFlag = true;
                            }
                            else if (content.Contains("Sorry we couldn't find that page."))
                            {
                                Log.Error($"404 Not found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                                _errorFlag = true;
                            }

                            // Check "go to section" mapping is wrong
                            if (url.Contains('#') && url.Contains("/docs/v2") && !url.Contains("api-reference") && !_edgeCaseUrls.Contains(url))
                            {
                                var expected = url.Split("docs/v2/").Last()
                                    .Replace('/', Path.DirectorySeparatorChar)
                                    .Replace('-', ' ')
                                    .Replace('#', Path.DirectorySeparatorChar)
                                    .Replace("Look ahead", "Look-ahead")    // special case
                                    .Replace("look ahead", "look-ahead")    // special case
                                    .Replace("profit loss", "profit-loss")    // special case
                                    .Replace("Built in", "Built-in")    // special case
                                    .Replace("scikit learn", "scikit-learn")    // special case
                                    .Replace("third party", "third-party")    // special case
                                    .Replace("C and Visual Studio", "C# and Visual Studio")    // special case
                                    .Replace("C and VS Code", "C# and VS Code")    // special case
                                    .Replace("C and Rider", "C# and Rider")    // special case
                                    .Replace("C and Rider", "C# and Rider")    // special case
                                    .Replace("mixed mode consolidators", "mixed-mode consolidators")    // special case
                                    .Replace("Multi Alpha", "Multi-Alpha")    // special case
                                    .Replace("Volatility3F", "Volatility%3F")      // special case
                                    .ToLower();
                                
                                var section = url.Split('#').Last()
                                    .Replace('-', ' ')
                                    .Replace("Look ahead", "Look-ahead")    // special case
                                    .Replace("look ahead", "look-ahead")    // special case
                                    .Replace("profit loss", "profit-loss")    // special case
                                    .Replace("Built in", "Built-in")    // special case
                                    .Replace("C and Visual Studio", "C# and Visual Studio")    // special case
                                    .Replace("C and VS Code", "C# and VS Code")    // special case
                                    .Replace("C and Rider", "C# and Rider")    // special case
                                    .Replace("Multi Alpha", "Multi-Alpha")     // special case
                                    .Replace("Volatility3F", "Volatility%3F");      // special case
                                var allFiles = Directory.GetFiles(_path, $"{section}.*", SearchOption.AllDirectories);
                                var noEquals = allFiles.All(dir => 
                                {
                                    var subPaths = dir.Split(Path.DirectorySeparatorChar).Where(x => int.TryParse(x.AsSpan(0, 1), out _));
                                    var nonNumberedPath = string.Join(Path.DirectorySeparatorChar, 
                                        subPaths.SkipLast(1).Select(x => new string(x.Where(c => (c < '0' || c > '9')).ToArray()).Trim()));
                                    var sectionPath = subPaths.Last().Split('.').First();

                                    return $"{nonNumberedPath}{Path.DirectorySeparatorChar}{sectionPath}".ToLower() != expected;
                                });

                                if (noEquals || allFiles.Length == 0)
                                {
                                    Log.Error($"No Section \"{section}\" was found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                                    _errorFlag = true;
                                }
                            }
                        })
                    );
                }
                catch (Exception e)
                {
                    Log.Error(e, $":\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                }

                if (i % 50 == 0)
                {
                    Log.Trace($"\tDone {i}/{count} ({Convert.ToDecimal(i)*100/count:0.00}%)");
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
        }

        /// <summary>
        /// Sends a GET request for the provided URL
        /// </summary>
        /// <param name="url">URL to send GET request for</param>
        /// <param name="files">files containing the url</param>
        /// <returns>Content as string</returns>
        /// <exception cref="Exception">Failed to request</exception>
        private static async Task<string> HttpRequester(string url, List<string> files)
        {
            try
            {
                using (var client = new HttpClient())
                {
                    var response = await client.GetAsync(url);
                    var statusCode = response.StatusCode;

                    if (statusCode == HttpStatusCode.BadRequest)
                    {
                        return $"400 Bad Request:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
                    }
                    else if (statusCode == HttpStatusCode.Unauthorized)
                    {
                        return $"403 Unauthorized:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
                    }
                    else if (statusCode == HttpStatusCode.NotFound)
                    {
                        return $"404 Not found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
                    }

                    response.EnsureSuccessStatusCode();
                    var content = await response.Content.ReadAsStringAsync();
                    response.Dispose();
                    
                    return content;
                }
            }
            catch
            {
                Thread.Sleep(1000);
            }

            return $"Fail to request:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]";
        }
    }
}