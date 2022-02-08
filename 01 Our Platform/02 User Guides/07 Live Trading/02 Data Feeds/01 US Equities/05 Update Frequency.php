<p>As we <a href='/docs/v2/our-platform/user-guides/live-trading/data-feeds/us-equities#04-Bar-Building'>build slices of ticks and bars</a>, we publish them to the cloud storage system. Lean checks the storage system for new data points at various frequencies, depending on the resolution of your data subscription. When new data points are available, they are injected into your algorithm. The following table shows the frequency of which Lean checks the storage system:</p>

<?php include(DOCS_RESOURCES."/live-dataset-polling-frequency-table.html"); ?>

<p>For example, if we receive a new data point at 9:51am for a dataset for which your algorithm has a daily subscription, your algorithm will discover the new data point between 9:51am and 10:21am.</p>