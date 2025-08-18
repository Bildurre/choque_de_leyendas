<?php
require_once 'vendor/autoload.php';
use Spatie\Browsershot\Browsershot;
echo "Testing Browsershot directly...\n";
try {
    $html = '<html><body><h1>Test Local</h1></body></html>';
    $tempFile = '/tmp/test-browsershot-local.png';
    
    Browsershot::html($html)
        ->windowSize(300, 200)
        ->save($tempFile);
    
    if (file_exists($tempFile)) {
        echo "✅ Browsershot works! File created: " . filesize($tempFile) . " bytes\n";
        unlink($tempFile);
    } else {
        echo "❌ File not created\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
