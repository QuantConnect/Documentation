<? if (!is_null($statusPageURL)) { ?>
<p>Note the following security and stability aspects of our <?=$brokerageName?> integration.</p>
<h4>Account Credentials</h4>
<? } ?>

<p>When you deploy live algorithms with <?=$brokerageName?>, we don't save your brokerage account credentials.</p>
<? if (!is_null($statusPageURL)) { ?>
<h4>API Outages</h4>
<p>We call the <?=$brokerageName?> API to place live trades. Sometimes the API may be down. Check the <a rel='nofollow' target='_blank' href='<?=$statusPageURL?>'><?=$brokerageName?> status page</a> to see if the API is currently working.</p>
<? } ?>
