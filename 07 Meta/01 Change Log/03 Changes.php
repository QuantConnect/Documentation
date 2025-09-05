<?php
require 'vendor/autoload.php'; // Include the AWS SDK

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// AWS configuration
$bucket = getenv('AWS_BUCKET'); // Read bucket name from AWS_BUCKET environment variable
$key = 'change-log.json'; // Path to the file in the S3 bucket (adjust if needed, e.g., Documentation/change-log.json)
$region = 'us-west-1'; // Replace with your bucket's region
$accessKey = getenv('AWS_KEY'); // Read access key from AWS_KEY environment variable
$secretKey = getenv('AWS_SECRET'); // Read secret key from AWS_SECRET environment variable

// Validate environment variables
if ($bucket === false || empty($bucket)) {
    throw new Exception('AWS_BUCKET environment variable is not set or empty');
}
if ($accessKey === false || empty($accessKey)) {
    throw new Exception('AWS_KEY environment variable is not set or empty');
}
if ($secretKey === false || empty($secretKey)) {
    throw new Exception('AWS_SECRET environment variable is not set or empty');
}

try {
    // Initialize the S3 client
    $s3Client = new S3Client([
        'region' => $region,
        'version' => 'latest',
        'credentials' => [
            'key' => $accessKey,
            'secret' => $secretKey,
        ],
    ]);

    // Retrieve the object from S3
    $result = $s3Client->getObject([
        'Bucket' => $bucket,
        'Key' => $key,
    ]);

    // Get the file content from the response
    $fileContent = $result['Body']->getContents();

    // Parse the JSON content
    $jsonData = json_decode($fileContent, true);

    // Check for JSON parsing errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error decoding JSON: ' . json_last_error_msg());
    }

    // Output the JSON data (for example purposes)
    echo "Successfully read change-log.json from S3:\n";
    print_r($jsonData);

} catch (AwsException $e) {
    // Handle AWS-specific errors
    echo "AWS Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage() . "\n";
}
?>
