<p>When you deploy live algorithms with <?=$brokerageName?>, we don't save your brokerage account credentials.</p>
<? if (!is_null($statusPageURL)) { ?>
<p>We call the <?=$brokerageName?> API to place live trades. Sometimes the API may be down. Check the <a rel='nofollow' target='_blank' href='$statusPageURL'><?=$brokerageName?> status page</a> to see if the API is currently working.</p>
<? } ?>
