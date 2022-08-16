<p>By our <a href='/terms'><span class='document-title'>Terms and Conditions</span></a>, you may not use the logs to export dataset information. The following table shows the amount of logs each organization tier can produce:</p>
<?php echo file_get_contents(DOCS_RESOURCES."/quotas/logs.html"); ?>

<p>If you delete a backtest or project that produced logs, your quotas aren't restored. Additionally, daily log quotas aren't fully restored at midnight. They are restored according to a 24-hour following window. </p>

<p>The log files of each project can store up to 100,000 lines for up to one year. If you log more than 100,000 lines or some lines become older than one year, we remove the oldest lines in the files so your project stays within the quota.</p>

<p>To avoid reaching the limits, we recommend logging sparsely, focusing on the change events instead of logging every time loop. You can use the <a href='/docs/v2/our-platform/user-guides/projects/debugging#03-Debugger'>debugger</a> to inspect objects during runtime. If you use the debugger, you should rarely reach the log limits.</p>
