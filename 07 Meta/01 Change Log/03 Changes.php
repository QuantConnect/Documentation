<?
$jsonUrl = 'https://s3.us-east-1.amazonaws.com/content.quantconnect.com/documentation/change-log.json';
$jsonData = file_get_contents($jsonUrl);
echo htmlspecialchars($jsonData !== false ? $jsonData : 'Failed to fetch JSON');
?>
Hello world3
