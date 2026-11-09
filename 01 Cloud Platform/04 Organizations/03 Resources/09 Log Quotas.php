<p>Per our <a href='/terms'><span class='document-title'>Terms and Conditions</span></a>, you may not use the logs to export dataset information. The following table shows the amount of logs each organization tier can produce:</p>
<?php echo file_get_contents(DOCS_RESOURCES."/quotas/logs.html"); ?>

<p>Reading or downloading backtest logs counts toward your daily log quota.</p>

<p>Each log line can contain up to 8,192 characters, including the timestamp that LEAN adds to the start of the line. The limit counts characters, so multi-byte UTF-8 characters each count as one character. If a line exceeds the limit, the log file keeps the first 8,192 characters and drops the rest without a warning or error.</p>

<p>If you delete a backtest or project that produced logs, your quotas aren't restored. Additionally, daily log quotas aren't fully restored at midnight. They are restored according to a 24-hour rolling window.</p>

<p>
  The log files of each live trading project can store up to 1,000,000 lines for up to two years. 
  If you log more than 1,000,000 lines or some lines become older than two years, we remove the oldest lines in the files so your project stays within the quota.
</p>

<p>To avoid reaching the limits, we recommend logging sparsely, focusing on the change events instead of logging every time loop. You can use the <a href='/docs/v2/cloud-platform/backtesting/debugging'>debugger</a> to inspect objects during runtime. If you use the debugger, you should rarely reach the log limits.</p>
