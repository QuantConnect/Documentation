<p>The following sections describe the latest updates to the documentation.</p>
<div class="highlight">As we continue to update and refactor the documentation on a regular basis, some links in the following sections may no longer work.</div>

<?
$content = file_get_contents('https://s3.us-east-1.amazonaws.com/cdn.quantconnect.com/docs/i/change-log.json');
$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
$decoded = json_decode($content, true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
    return;
}
$changesByDate = array_reverse($decoded);
foreach ($changesByDate as $date => $changes) {
    if (is_string($changes)) {
        $changes = mb_convert_encoding($changes, 'UTF-8', 'UTF-8');
        $changes = json_decode($changes, true);
    }
    if (empty($changes)) {
      continue;
    }
    echo "<h4>$date</h4>";
    echo "<ul>";
    foreach ($changes as $change) {
        $url = $change["url"];
        $summary = $change["summary"];
        echo "<li>[<a href='$url'>Source</a>] $summary</li>";
    }
    echo "</ul>";
}
?>

