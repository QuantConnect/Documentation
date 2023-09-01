<p>When you deploy a live algorithm, you can access the data within minutes of modifying the Object Store. Ensure your algorithm is able to handle a changing dataset.</p>

<? if ($cloudPlatform) { ?>
<p>The live environment's access to the Object Store is much slower than in research and backtesting. Limit the individual objects to less than 50 MB to prevent live trading access issues.</p>
<? } ?>