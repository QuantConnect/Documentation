<p>The amount of logs you can generate per backtest and per day depends on the tier of your organization. The following table shows the amount of logs you can generate on each tier:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/quotas/logs.html"); ?>

<p>Each log line can contain up to 8,192 characters, including the timestamp that LEAN adds to the start of the line. The limit counts characters, so multi-byte UTF-8 characters each count as one character. If a line exceeds the limit, the log file keeps the first 8,192 characters and drops the rest without a warning or error.</p>
