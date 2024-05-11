<p>
    Runs a local optimization in a Docker container using the <a href="https://hub.docker.com/r/quantconnect/lean" target="_blank" rel="nofollow">quantconnect/lean</a> Docker image.
    The logs of the optimizer are shown in real-time and the full results of the optimizer and all executed backtests are stored in the <span class="public-directory-name">&lt;project&gt; / optimizations / &lt;timestamp&gt;</span> directory.
    You can use the <code>--output</code> option to change the output directory.
</p>

<p>
    The given <code>&lt;project&gt;</code> argument must be either a project directory or a file containing the algorithm to optimize.
    If it is a project directory, the CLI looks for a <span class="public-file-name">main.py</span> or <span class="public-file-name">Main.cs</span> file, assuming the first file it finds to contain the algorithm to optimize.
</p>

<p>
    By default, an interactive wizard is shown letting you configure the optimizer.
    When <code>--optimizer-config</code> or <code>--strategy</code> is given, the command runs in non-interactive mode and doesn't prompt for input.
</p>

<p>
    When the <code>--optimizer-config &lt;config file&gt;</code> option is given, the specified config file is used.
    This option must point to a file containing a full optimizer config (the <code>algorithm-type-name</code>, <code>algorithm-language</code> and <code>algorithm-location</code> properties may be omitted).
    See the <a href="https://github.com/QuantConnect/Lean/blob/master/Optimizer.Launcher/config.example.json" target="_blank" class="public-file-name">Optimizer.Launcher / config.example.json</a> file in the LEAN repository for an example optimizer configuration file, which also contains documentation on all the required properties.
</p>

<p>
    When <code>--strategy</code> is given, the optimizer configuration is read from the command-line options.
    This means the <code>--strategy</code>, <code>--target</code>, <code>--target-direction</code>, and <code>--parameter</code> options become required.
    Additionally, you can also use <code>--constraint</code> to specify optimization constraints.
</p>

<p>
    In non-interactive mode, the parameters can be configured using the <code>--parameter</code> option.
    This option takes the following values: the name of the parameter, its minimum value, its maximum value, and its step size.
    You can provide this option multiple times to configure multiple parameters.
</p>

<p>
    In non-interactive mode, the constraints can be configured using the <code>--constraint</code> option.
    This option takes a "statistic operator value" string as value, where the statistic must be a path to a property in a backtest's output file, like "TotalPerformance.PortfolioStatistics.SharpeRatio".
    This statistic can also be shortened to "SharpeRatio" or "Sharpe Ratio", in which case the command automatically converts it to the longer version.
    The value must be a number and the operator must be <code>&lt;</code>, <code>&gt;</code>, <code>&lt;=</code>, <code>&gt;=</code>, <code>==</code>, or <code>==</code>.
    You can provide this option multiple times to configure multiple constraints.
</p>

<? include(DOCS_RESOURCES."/cli/backtest-data-provider.html"); ?>

<p>
    Example non-interactive usage:
</p>

<div class="cli section-example-container">
<pre>$ lean optimize "My Project" \
    --strategy "Grid Search" \
    --target "Sharpe Ratio" \
    --target-direction "max" \
    --parameter my-first-parameter 1 10 0.5 \
    --parameter my-second-parameter 20 30 5 \
    --constraint "Drawdown &lt; 0.5" \
    --constraint "Sharpe Ratio &gt;= 1"</pre>
</div>

<p>To estimate the cost of running an optimization job without actually running it, add the <code>--estimate</code> option to the command. You need to backtest the project at least once in order to estimate the cost of optimizing it.</p>

<p>To set the maximum number of concurrent backtests to run, use the <code>--max-concurrent-backtests</code> option.</p>

<p>
    The Docker image that's used contains the same libraries as the ones <a href="/docs/v2/lean-cli/projects/libraries/third-party-libraries">available on QuantConnect</a>.
    If the selected project is a C# project, it is compiled before starting the optimization.
</p>

<p>
    By default, the official LEAN engine image is used.
    You can override this using the <code>--image &lt;value&gt;</code> option.
    Alternatively, you can set the default engine image for all commands using <code>lean config set engine-image &lt;value&gt;</code>.
    The image is pulled before running the optimizer if it doesn't exist locally yet or if you pass the <code>--update</code> flag.
</p>
