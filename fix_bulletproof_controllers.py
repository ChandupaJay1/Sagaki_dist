import os
import re

controllers = {
    'SalesReturnController.php': {'var': '$salesReturn', 'print_route': 'sales-returns.show'},
    'SalesOrderController.php': {'var': '$salesOrder', 'print_route': 'sales-orders.show'},
    'PurchaseOrderController.php': {'var': '$purchaseOrder', 'print_route': 'purchase-orders.show'},
    'InventoryTransferController.php': {'var': '$transfer', 'print_route': 'inventory-transfers.show'},
    'GrnReturnController.php': {'var': '$grnReturn', 'print_route': 'grn-returns.show'},
    'GrnController.php': {'var': '$grn', 'print_route': 'grns.show'}
}

for ctrl, conf in controllers.items():
    filepath = os.path.join('app/Http/Controllers', ctrl)
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        var_name = conf['var']
        print_route = conf['print_route']

        old_injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            return response()->json([
                'success' => true,
                'print_url' => route('{print_route}', {var_name}->id),
            ]);
        }}"""

        new_injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            if ($request->ajax() || $request->wantsJson()) {{
                return response()->json([
                    'success' => true,
                    'print_url' => route('{print_route}', {var_name}->id),
                ]);
            }}
            return redirect()->route('{print_route}', {var_name}->id)->with('success', 'Saved successfully.');
        }}"""

        if old_injection in content:
            content = content.replace(old_injection, new_injection)
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Updated {ctrl}")
        else:
            print(f"Could not find exact block to replace in {ctrl}")
    else:
        print(f"File {ctrl} not found")

