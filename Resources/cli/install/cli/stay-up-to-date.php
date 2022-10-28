<?php
$getUpdateText = function($isCLIDocs)
{
	echo "

<p>
    We regularly update the CLI to add new features and to fix issues.
    Therefore, it's important to keep both the CLI and the Docker images that the CLI uses up-to-date.
</p>
";
	if ($isCLIDocs)
	{
		echo "<h4>Keep the CLI Up-To-Date</h4>";
	}

	echo "
<p>
    To update the CLI to the latest version, run <code>pip install --upgrade lean</code>.
    The CLI automatically performs a version check once a day and warns you in case you are running an outdated version.
</p>
	";
}
?>