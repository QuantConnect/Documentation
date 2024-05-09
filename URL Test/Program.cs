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
using System.Text.Json;
using System.Text.RegularExpressions;
using System.Threading;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace UrlCheck
{
    public class Strategy
    {
        public string Name { get; set; }
        public string Link { get; set; }
        public Dictionary<string, string> Sources { get; set; } = new();
        public string Description { get; set; }
        public string Tags { get; set; }
    }

    public class Program
    {
        const string path = "..";
        const string root = "https://www.quantconnect.com/";
        const string leanIo = "https://www.lean.io/";
        const string strategyPhp = "../03 Writing Algorithms/42 Strategy Library/02 Tutorials.php";
        static readonly string[] leanIoFolder = new string[] {"05 Lean CLI", "06 LEAN Engine"};

        static void Main()
        {
            var leanIoErrorUrls = new string[] {
                "/docs/v2/cloud-platform", "/docs/v2/local-platform", "/docs/v2/writing-algorithm",
                "/docs/v2/research-environment"
            };
            var edgeCaseUrls = new []
            {
                "https://www.quantconnect.com/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates"
            };

            var stopwatch = Stopwatch.StartNew();
            var i = 1;
            var tasks = new List<Task>();
            var errorFlag = false;

            var urlFiles = GetAllUrls();
            var strategyUrlFile = GetStrategyPhpUrls();
            urlFiles = urlFiles.Concat(strategyUrlFile).ToDictionary(kvp => kvp.Key, kvp => kvp.Value);
            var resourceFiles = GetResourcesRedir();
            var count = urlFiles.Count;
            Log.Trace($"Start Testing {count} URLs...");

            var emptyPages = Directory.GetDirectories(path, "*.*", SearchOption.AllDirectories)
                .Where(filter)
                .Where(x => Directory.GetFiles(x).Length == 0)
                .Select(x => pathToLink(x))
                .ToHashSet();

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

                            if (url.Contains($"{leanIo}docs/v2/"))
                            {
                                if (leanIoErrorUrls.Any(url.Contains))
                                {
                                    Log.Error($"Lean.io non-existence:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                                    errorFlag = true;
                                }
                            }

                            if (emptyPages.Contains(url))
                            {
                                Log.Error($"Empty page:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
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
                            else if (url.Contains("api.github.com/repos/QuantConnect/Lean/issues"))
                            {
                                var state = JsonDocument.Parse(content).RootElement.GetProperty("state").GetString();
                                if (state != "open")
                                {
                                    Log.Error($"The GitHub issue is not open:\n\t{url}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
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

            Log.Trace($"Now check {resourceFiles.Count()} RESOURCE redirection.");

            foreach (var (subPath, files) in resourceFiles)
            {
                try
                {
                    if (!Directory.Exists($"../Resources/{subPath}") && !File.Exists($"../Resources/{subPath}"))
                    {
                        Log.Error($"Non-existing resource page:\n\t\"Resources/{subPath}\"\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                        errorFlag = true;
                    }
                }
                catch (Exception e)
                {
                    Log.Error(e, $":\n\t{subPath}\n\t[\n\t\t{string.Join("\n\t\t", files)}\n\t]");
                }

                if (i % 100 == 0)
                {
                    Log.Trace($"\tDone {i}/{count} ({Convert.ToDouble(i) / count:P2})");
                }
            }

            Log.Trace($"Finished in {stopwatch.Elapsed.ToStringInvariant(null)}");
            if (errorFlag)
            {
                throw new Exception("There is/are broken link(s) in the docs! Refer to the above logs for reference.");
            }
        }


        private static Dictionary<string, List<string>> GetAllUrls()
        {
            Dictionary<string, List<string>> urlFiles = new();

            var allFiles = Directory.GetFiles(path, "*.*", SearchOption.AllDirectories)
                .Where(filter)
                .OrderBy(x => x);

            foreach (var file in allFiles)
            {
                foreach (var line in File.ReadAllLines(file))
                {
                    var end = line.IndexOf("href");
                    if (end < 0 ) continue;
                    var start = line[..end].IndexOf("<a");
                    if (start < 0) continue;

                    if (line[end..].Contains("<?=")) continue;
                    if (line[..start].Contains("<!--")) continue;

                    var hrefs = (line[..(3 + start)] + line[end..]).Replace('\'', '\"').Split("a href=\"").Skip(1);
                    foreach (var href in hrefs)
                    {
                        var url = href.Split('\"').First();
                        var urlLeanIo = String.Empty;
                        var subUrl = String.Empty;

                        if (string.IsNullOrWhiteSpace(url) || url.Contains('{') || url.Contains('}')) continue;

                        if (Regex.IsMatch(url, @"github.com/QuantConnect/Lean/issues/\d+"))
                        {
                            url = url.Replace("github.com", "api.github.com/repos").Replace("www", String.Empty);
                        }

                        if (!url.Contains("http"))
                        {
                            if (url[0] == '#')
                            {
                                url = pathToLink(file, 1) + url;
                            }
                            else if (url.Contains("mailto:"))
                            {
                            }
                            else if (url[0] != '/')
                            {
                                url = pathToLink(file, 2) + $"/{url}";
                            }
                            else
                            {
                                url = $"{root}{url.Remove(0, 1)}";
                            }

                            if (leanIoFolder.Any(file.Contains))
                            {
                                urlLeanIo = url.Replace(root, leanIo);
                            }
                        }

                        if (url.Contains("sources")) continue;

                        if (!urlFiles.ContainsKey(url))
                        {
                            urlFiles.Add(url, new List<string>());
                        }

                        urlFiles[url].Add(file);

                        if (!string.IsNullOrWhiteSpace(urlLeanIo))
                        {
                            if (!urlFiles.ContainsKey(urlLeanIo))
                            {
                                urlFiles.Add(urlLeanIo, new List<string>());
                            }

                            urlFiles[urlLeanIo].Add(file);
                        }
                    }
                }
            }

            return urlFiles;
        }

        private static Dictionary<string, List<string>> GetStrategyPhpUrls()
        {
            var fileString = File.ReadAllText(strategyPhp);
            var phpDictString = string.Join(' ', fileString.Split("= [").Skip(1)).Split("];").First().Trim();
            var jsonDictList = $"[{phpDictString.Replace("=>", ":").Replace('[', '{').Replace(']', '}')}]";

            var dictionaryList = JsonConvert.DeserializeObject<List<Strategy>>(jsonDictList);
            List<String> urls = new();
            foreach (var dictionary in dictionaryList)
            {
                var link = $"https://www.quantconnect.com/tutorials/{dictionary.Link}";
                urls.Add(link);

                var sourcesUrls = dictionary.Sources.Values.ToList();
                urls.AddRange(sourcesUrls);
            }

            return new Dictionary<string, List<string>> {{strategyPhp, urls}};
        }

        private static Dictionary<string, List<string>> GetResourcesRedir()
        {
            Dictionary<string, List<string>> resourceFiles = new();

            var allFiles = Directory.GetFiles(path, "*.*", SearchOption.AllDirectories)
                .Where(filter)
                .OrderBy(x => x);

            foreach (var file in allFiles)
            {
                foreach (var line in File.ReadAllLines(file))
                {
                    var splitted = line.Split("<? include(DOCS_RESOURCES.\"");
                    if (splitted.Count() < 2) continue;

                    if (splitted[0].Contains("<!--")) continue;

                    foreach (var rref in splitted.Skip(1))
                    {
                        var subDir = rref.Split('\"').First();

                        if (subDir[0] == '/')
                        {
                            subDir = subDir.Substring(1);
                        }

                        if (string.IsNullOrWhiteSpace(subDir) || subDir.Contains('{') || subDir.Contains('}')) continue;

                        if (!resourceFiles.ContainsKey(subDir))
                        {
                            resourceFiles.Add(subDir, new List<string>());
                        }

                        resourceFiles[subDir].Add(file);
                    }
                }
            }

            return resourceFiles;
        }

        private static async Task<string> HttpRequester(string url, List<string> files)
        {
            try
            {
                using var client = new HttpClient();
                client.DefaultRequestHeaders.Add("User-Agent", "Mozilla/5.0 (compatible; AcmeInc/1.0)");
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
                };

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

        private static bool filter(string x)
        {
            // Exclude Documentation Updates since it may include broken links
            // Exclude single-page docs since it is generated from basic docs
            return !x.Contains(".git") && !x.Contains(".vs") && !x.Contains("single-page") && !x.Contains("08 Drafts") &&
            !x.EndsWith("Documentation Updates.html");
        }

        private static string pathToLink(string x, int count = 0)
        {
            var values = x[Math.Min(3, path.Length)..]
                .Split(Path.DirectorySeparatorChar, StringSplitOptions.RemoveEmptyEntries)
                .SkipLast(count)
                .Select(x => x.Remove(0, 2).Trim());
            return $"{root}docs/v2/" + string.Join("/", values).ToLower().Replace(" ", "-");
        }
    }
}