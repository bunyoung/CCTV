<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$bucket = 'your-s3-bucket-name';
$region = 'your-region';

$s3 = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'credentials' => [
        'key' => 'YOUR_AWS_ACCESS_KEY_ID',
        'secret' => 'YOUR_AWS_SECRET_ACCESS_KEY',
    ]
]);

$dir = __DIR__ . '/captures';
$files = array_diff(scandir($dir), ['.', '..']);

foreach ($files as $file) {
    $filePath = $dir . '/' . $file;

    try {
        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => 'backup/' . $file,
            'SourceFile' => $filePath,
            'ACL' => 'private'
        ]);
        echo "Uploaded: $file\n";
        // ลบไฟล์หลังอัปโหลด (ถ้าต้องการ)
        // unlink($filePath);
    } catch (AwsException $e) {
        echo "Error uploading $file: " . $e->getMessage() . "\n";
    }
}
