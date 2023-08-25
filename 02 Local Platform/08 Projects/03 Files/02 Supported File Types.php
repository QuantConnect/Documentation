<? include(DOCS_RESOURCES."/projects/files/supported-file-types.php"); ?>

<h4>Code Files</h4>
<p>The <span class='public-file-name'>.py</span>/<span class='public-file-name'>.cs</span> files are code files. These are the files where you implement your trading algorithm. When you backtest the project or deploy the project to live trading, the LEAN engine executes the algorithm you define in these code files.</p>

<h4>Notebook Files</h4>
<p>The <span class='public-file-name'>.ipynb</span> files are notebook files. These are the files you open when you want to access the <a href='/docs/v2/research-environment'>Research Environment</a> to perform quantitative research.</p>

<h4>Custom Report Files</h4>
<p>The <span class='public-file-name'>.html</span>/<span class='public-file-name'>.css</span> files are for creating custom <a href='/docs/v2/local-platform/backtesting/results#09-Reports'>reports</a>.</p>

<h4>Configuration Files</h4>
<p>Projects also contain configuration files, which are <span class='public-file-name'>.json</span> files, but they aren't displayed in the Explorer panel. These files contain information like the project description, parameters, and shared libraries. For more information about project configuration files, see <a href='/docs/v2/lean-cli/projects/configuration'>Configuration</a>.</p>

<h4>Result Files</h4>
<p>When you run a backtest, optimize some parameters, or deploy a strategy to live trading on your local machine, the results are saved as phyical files in the project directory. Local Platform doesn't push these result files to QuantConnect Cloud.</p>
