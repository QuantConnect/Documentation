<p>We aggregate a stream of ticks into bars as we receive the ticks throughout the day. When we close a bar, we publish it to the cloud storage. Lean checks the storage system for new data points at various frequencies, depending on the resolution of your data subscription. When Lean finds new data points, they are injected into your algorithm. The following table shows the frequency of which Lean checks the storage system:</p>

<?php include(DOCS_RESOURCES."/live-dataset-polling-frequency-table.html"); ?>

<p>For example, if we receive a new data point at 9:51am for a dataset for which your algorithm has a daily subscription, your algorithm will discover the new data point between 9:51am and 10:21am.</p>
