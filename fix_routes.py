import os
import re

controllers = [
    'SalesReturnController.php',
    'SalesOrderController.php',
    'PurchaseOrderController.php',
    'InventoryTransferController.php',
    'GrnReturnController.php'
]

for ctrl in controllers:
    filepath = os.path.join('app/Http/Controllers', ctrl)
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Replace .print with .show in the injected logic
        content = content.replace("route('sales-returns.print'", "route('sales-returns.show'")
        content = content.replace("route('sales-orders.print'", "route('sales-orders.show'")
        content = content.replace("route('purchase-orders.print'", "route('purchase-orders.show'")
        content = content.replace("route('grn-returns.print'", "route('grn-returns.show'")
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed {ctrl}")

