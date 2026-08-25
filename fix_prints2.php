<?php

$files = [
    ['app/Http/Controllers/SalesOrderController.php', 'sales-orders', 'order'],
    ['app/Http/Controllers/SalesReturnController.php', 'sales_returns', 'return'],
    ['app/Http/Controllers/PurchaseOrderController.php', 'purchase_orders', 'order'],
    ['app/Http/Controllers/GrnController.php', 'grns', 'grn'],
    ['app/Http/Controllers/GrnReturnController.php', 'grn_returns', 'return'],
    ['app/Http/Controllers/InventoryTransferController.php', 'inventory_transfers', 'transfer']
];

foreach ($files as $f) {
    $content = file_get_contents($f[0]);
    if (strpos($content, 'public function print(') === false) {
        // Find show method
        preg_match('/public function show\([^)]+\)\s*\{(.*?)\}/s', $content, $matches);
        if ($matches) {
            $showBody = $matches[1];
            // change the view return from .show to .print
            $printBody = preg_replace('/view\(([\'"])[^\'"]+\.show/', "view(" . $f[1] . ".print", $showBody);
            $printMethod = "\n    public function print(\)\n    {" . $printBody . "}\n";
            
            // replace last brace manually
            $pos = strrpos($content, '}');
            if ($pos !== false) {
                $content = substr_replace($content, $printMethod . "}\n", $pos, 1);
            }
            file_put_contents($f[0], $content);
        }
    }
}
echo "Done\n";
