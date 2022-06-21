<?php echo file_get_contents(DOCS_RESOURCES."/time-zone.html"); ?>

<p>The <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#04-Algorithm-Time-Zone'>algorithm time zone</a> may be different from the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>. If the time zones are different, it might appear like there is a lag between the algorithm time and the first bar of a history request, but this is just the difference in time zone. All the data is internally synchronized in Coordinated Universal Time (UTC) and arrives in the same <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> object. A slice is a sliver of time with all the data available for this moment.</p>

<p>To keep trades easy to compare between asset classes, we mark all orders in UTC time.</p>
