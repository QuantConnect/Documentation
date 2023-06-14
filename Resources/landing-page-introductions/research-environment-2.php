<p>The Research Environment is a <a rel="nofollow" target="_blank" href="https://jupyter-notebook.readthedocs.io/en/stable/notebook.html">Jupyter notebook</a>-based, interactive commandline environment where you can access <?=$localPlatform ? "your local data or our cloud data":"our data"?> through the <code>QuantBook</code> class. The environment supports both Python and C#. If you use Python, you can import code from the code files in your project into the Research Environment to aid development.</p>

<p>Before you run backtests, we recommend testing your hypothesis in the Research Environment. It's easier to perform data analysis and <a href="/docs/v2/research-environment/charting">produce plots in the Research Environment</a> than in a backtest.</p>

<p>Before backtesting or live trading with machine learning models, you may find it beneficial to train them in the Research Environment, save them in the ObjectStore, and then load them from the ObjectStore into the backtesting and live trading environment</p>

<p>In the Research Environment, you can also use the QuantConnect API to <a href="/docs/v2/research-environment/meta-analysis/backtest-analysis">import your backtest results</a> for further analysis.</p>
