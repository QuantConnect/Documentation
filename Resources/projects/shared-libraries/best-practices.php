<?php
$fileQuota = isset($fileQuotaLink) ? "<a href='{$fileQuotaLink}'>file quota</a>" : "file quota";
$library = isset($sharedLibrariesLink) ? "<a href='{$sharedLibrariesLink}'>library</a>" : "library";
?>
<p>Each library is a project, so it has its own <?=$fileQuota?>. If a project outgrows its quota, move part of the code into a <?=$library?>.</p>

<p>Libraries are shared, so when you edit one, every project that uses it picks up the change. Add code to a library when it's stable and several projects need it. Keep work in progress and code that only one project uses in the project itself.</p>
