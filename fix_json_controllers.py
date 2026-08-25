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

        # Replace the old injected redirect block with the JSON response
        old_injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            return redirect()->route('{print_route}', {var_name}->id)->with('success', 'Saved successfully.');
        }}"""
        
        # for GrnController, it was:
        old_grn_injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            return redirect()
                ->route('grns.show', {var_name}->id) // GRN uses show for print usually or grns.print if exists. The script used grns.print. I will check.
                ->with('success', 'GRN created successfully.');
        }}"""

        new_injection = f"""if ($request->input('action') === 'save_and_print' && {var_name}) {{
            return response()->json([
                'success' => true,
                'print_url' => route('{print_route}', {var_name}->id),
            ]);
        }}"""

        if old_injection in content:
            content = content.replace(old_injection, new_injection)
        elif old_grn_injection in content:
            content = content.replace(old_grn_injection, new_injection)
        else:
            print(f"Could not find exact block to replace in {ctrl}")
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {ctrl}")
    else:
        print(f"File {ctrl} not found")

