<?
$deployBacktestLink = $cloudPlatform ? "/docs/v2/cloud-platform/backtesting/deployment#08-Run-Backtests" : "/docs/v2/local-platform/backtesting/deployment#08-Run-Backtests";
?>

<p>The backtest results page automatically displays when you <a href='<?=$deployBacktestLink?>'>deploy a backtest</a>. The backtest results page presents the equity curve, trades, logs, performance statistics, and much more information.</p>

<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/backtest-result-page-top.png" alt="Backtest result interface">

<? if ($cloudPlatform) { ?>
<p>The content in the backtest results page updates as your backtest executes. You can close or refresh the window without interrupting the backtest because the backtesting node processes on our servers. If you close the page, to open the page again, <a href="/docs/v2/cloud-platform/backtesting/results#16-View-All-Backtests">view all of the project's backtests</a>. Unless you explicitly make the backtest public, only you can view its results. If you delete a backtest result or you are inactive for 12 months, we archive your backtest results.</p>
<? } else { ?>
<p>The content in the backtest results page updates as your backtest executes. You can close Local Platform without interrupting the backtest as long as you keep Docker running. If you close the page, to open it again, <a href="/docs/v2/local-platform/backtesting/results#17-View-All-Backtests">view all of the project's backtests</a>. Only you can view the results of local backtests. If you run the backtest in QuantConnect Cloud, only you can view its results unless you explicitly make the backtest public. If you delete a backtest result or you are inactive for 12 months, we archive your backtest results.</p>

<p>The information on the backtest results page is also available in its raw form. To access it, see <a href='/docs/v2/local-platform/backtesting/results#15-View-Result-Files'>View Result Files</a>.</p>
<? } ?>

