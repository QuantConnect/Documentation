<?
$changeByDate = json_decode(file_get_contents('https://s3.us-east-1.amazonaws.com/cdn.quantconnect.com/docs/i/change-log.json'), true);

foreach ($changeByDate as $date => $changes) {
    $changes = json_decode($changes, true);
    if (empty($changes))) {
      continue;
    }
?>
    <h4><?=$date?></h4>
    <ul>
<?
    foreach ($change in $changes) {
?>
      <li><?=$change["summary"]?></li>
<?
    }
?>
    </ul>
<? 
} 
?>

