<?php

$files = [
    ['app/Http/Controllers/SalesOrderController.php', 'sales-orders'],
    ['app/Http/Controllers/SalesReturnController.php', 'sales-returns'],
    ['app/Http/Controllers/PurchaseOrderController.php', 'purchase-orders'],
    ['app/Http/Controllers/GrnController.php', 'grns'],
    ['app/Http/Controllers/GrnReturnController.php', 'grn-returns'],
    ['app/Http/Controllers/InventoryTransferController.php', 'inventory-transfers']
];

foreach ($files as $f) {
    $content = file_get_contents($f[0]);
    
    // 1. Change save_and_print route
    $content = preg_replace("/('print_url'\s*=>\s*route\('$f[1])\.show'/", ".print'", $content);
    $content = preg_replace("/return redirect\(\)->route\('$f[1]\.show'/", "return redirect()->route('$f[1].print'", $content);

    // 2. Add print method
    if (strpos($content, 'public function print(') === false) {
        // Find show method
        preg_match('/public function show\(\\)\s*\{(.*?)\}/s', $content, $matches);
        if ($matches) {
            $showBody = $matches[1];
            $printBody = str_replace('.show', '.print', $showBody);
            $printMethod = "\n    public function print(\)\n    {" . $printBody . "}\n";
            
            // Inject before the last brace
            $content = preg_replace('/\}\s*$/', $printMethod . "}\n", $content);
        }
    }
    
    file_put_contents($f[0], $content);
}

// Invoice Controller (only needs redirect fix)
$content = file_get_contents('app/Http/Controllers/InvoiceController.php');
$content = preg_replace("/('print_url'\s*=>\s*route\('invoices)\.show'/", ".print'", $content);
$content = preg_replace("/return redirect\(\)->route\('invoices\.show'/", "return redirect()->route('invoices.print'", $content);
file_put_contents('app/Http/Controllers/InvoiceController.php', $content);

// PayBill Controller
$content = file_get_contents('app/Http/Controllers/PayBillController.php');
$content = preg_replace("/('print_url'\s*=>\s*route\('pay-bills)\.show'/", ".print'", $content);
$content = preg_replace("/return redirect\(\)->route\('pay-bills\.show'/", "return redirect()->route('pay-bills.print'", $content);
file_put_contents('app/Http/Controllers/PayBillController.php', $content);

echo "Done\n";
