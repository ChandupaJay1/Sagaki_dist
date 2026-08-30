<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = app()->make(\App\Http\Controllers\Api\VendorController::class);
    $response = $controller->getOutstandingBills(1); // 1 is the ID of Demo Vendor we created
    echo $response->getContent();
} catch (\Throwable $e) { // catch ALL errors
    echo "ERROR:\n" . $e->getMessage() . "\n" . $e->getTraceAsString();
}
