<?
// URL of the JSON file
$jsonUrl = 'https://s3.us-east-1.amazonaws.com/content.quantconnect.com/documentation/change-log.json';

// Fetch JSON data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $jsonUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$jsonData = curl_exec($ch);
curl_close($ch);

echo htmlspecialchars($jsonData !== false ? $jsonData : 'Failed to fetch JSON');
?>
Hello world2
