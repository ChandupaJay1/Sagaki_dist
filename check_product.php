<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$products = \App\Models\Product::limit(5)->get();
foreach($products as $p) {
    echo "ID: $p->id, Code: $p->code, Max Sale Price: $p->max_sale_price, Cost: $p->cost, Qty: $p->qty, Onhand: $p->onhand\n";
}

