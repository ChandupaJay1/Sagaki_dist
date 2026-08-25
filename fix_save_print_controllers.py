import os
import re

controllers = {
    'SalesReturnController.php': {'var': '$salesReturn', 'print_route': 'sales-returns.print'},
    'SalesOrderController.php': {'var': '$salesOrder', 'print_route': 'sales-orders.print'},
    'PurchaseOrderController.php': {'var': '$purchaseOrder', 'print_route': 'purchase-orders.print'},
    'InventoryTransferController.php': {'var': '$transfer', 'print_route': 'inventory-transfers.show'},
    'GrnReturnController.php': {'var': '$grnReturn', 'print_route': 'grn-returns.print'},
    'GrnController.php': {'var': '$grn', 'print_route': 'grns.print'}
}

for ctrl, conf in controllers.items():
    filepath = os.path.join('app/Http/Controllers', ctrl)
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # We want to replace the first `return redirect()->route('xxx.index')->with('success', ...);`
        # in the store method with the conditional check.
        # It's usually right at the end of store method.

        # Let's find the store method content
        store_match = re.search(r'(public function store\(Request \$request\).*?)(return redirect\(\)->route\([^\)]+\)->with\([^\)]+\);)', content, flags=re.DOTALL)
        if store_match:
            # We construct the injection string
            var_name = conf['var']
            print_route = conf['print_route']
            
            injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            return redirect()->route('{print_route}', {var_name}->id)->with('success', 'Saved successfully.');
        }}

        {store_match.group(2)}"""

            # Replace inside the whole file
            new_content = content.replace(store_match.group(2), injection, 1)

            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Updated {ctrl}")
        else:
            print(f"Could not find return redirect in {ctrl}")
    else:
        print(f"File {ctrl} not found")

