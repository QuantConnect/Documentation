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
using System.Net.Http.Headers;
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
        private static readonly string _root = "https://www.quantconnect.com/";
        private static Dictionary<string, List<string>> _urlFiles = new();

        /// <summary>
        /// URL tester program
        /// </summary>
        public static void Main()
        {
            _urlFiles = GetAllUrls("../");
            TestUrls(_urlFiles);
        }

        /// <summary>
        /// Get all urls from all files in a root dir
        /// </summary>
        /// <param name="path">root dir</param>
        /// <return>A dictionary with url as key and list of files containing the url as values</return>
        private static Dictionary<string, List<string>> GetAllUrls(string path)
        {
            var allUrls = new Dictionary<string, List<string>>();
            var allFiles = Directory.GetFiles("..", "*.*", SearchOption.AllDirectories);

            foreach(var file in allFiles)
            {
                foreach(var line in File.ReadAllLines(file))
                {
                    if (line.Contains("a href"))
                    {
                        var hrefs = line.Split("a href=\"").Skip(1);
                        foreach(var href in hrefs)
                        {
                            var url = href.Split('\"').First();

                            if (!url.Contains("http"))
                            {
                                if (url[0] != '/')
                                {
                                    var subUrl = string.Join("/", file.Split(Path.PathSeparator).SkipLast(2)).ToLower().Replace(" ", "-");
                                    url = $"{_root}{subUrl}/{url}";
                                }
                                else
                                {
                                    url = $"{_root}{url}";
                                }
                            }

                            if (!allUrls.ContainsKey(url))
                            {
                                allUrls.Add(url, new List<string>());
                            }

                            allUrls[url].Add(file);
                        }
                    }
                }
            }

            return allUrls;
        }

        /// <summary>
        /// Test all urls if successfully get
        /// </summary>
        /// <param name="urls">URLs to be tested</param>
        private static void TestUrls(Dictionary<string, List<string>> urls)
        {
            var stopwatch = Stopwatch.StartNew();
            Log.Trace($"Start Testing URLs...");

            var i = 1;
            var count = urls.Count();
            var tasks = new List<Task>();

            foreach (var kvp in urls)
            {
                var url = kvp.Key;
                var files = kvp.Value;

                try
                {        
                    tasks.Add(
                        HttpRequester(url, files).ContinueWith(response => {
                            var statusCode = response.Result;
                            
                            if (statusCode == HttpStatusCode.NotFound)
                            {
                                Log.Error($"404 Not found:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                            }
                            else if (statusCode == HttpStatusCode.Unauthorized)
                            {
                                Log.Trace($"403 Unauthorized:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                            }
                            else if (statusCode == HttpStatusCode.BadRequest)
                            {
                                Log.Trace($"400 Bad Request:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                            }
                        })
                    );
                }
                catch (Exception e)
                {
                    Log.Error(e, $":\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                }

                if (i % 20 == 0)
                {
                    Log.Trace($"\tDone {i}/{count} ({i/count:0.00}%)");
                }
                
                Interlocked.Increment(ref i);

                if (tasks.Count == 20)
                {
                    Task.WaitAll(tasks.ToArray());
                    tasks.Clear();
                }
            }
                
            Log.Trace($"Finished in {stopwatch.Elapsed.ToStringInvariant(null)}");
        }

        /// <summary>
        /// Sends a GET request for the provided URL
        /// </summary>
        /// <param name="url">URL to send GET request for</param>
        /// <param name="files">files containing the url</param>
        /// <returns>Content as string</returns>
        /// <exception cref="Exception">Failed to get data after exceeding retries</exception>
        private static async Task<HttpStatusCode> HttpRequester(string url, List<string> files)
        {
            try
            {
                using (var client = new HttpClient())
                {
                    var response = await client.GetAsync(url);
                    var status = response.StatusCode;

                    
                    return status;
                }
            }
            catch
            {
                return HttpStatusCode.BadRequest;
            }
        }
    }
}