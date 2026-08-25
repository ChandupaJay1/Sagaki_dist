import os
import re

views_to_update = [
    'resources/views/sales_returns/create.blade.php',
    'resources/views/sales_orders/create.blade.php',
    'resources/views/purchase_orders/create.blade.php',
    'resources/views/inventory_transfers/create.blade.php',
    'resources/views/grn_returns/create.blade.php',
    'resources/views/grns/create.blade.php'
]

injection = """                    // "Save & Print" - server returns JSON with print_url after creating.
                    if (actionValue === 'save_and_print' && response.ok) {
                        return response.json().then(function (data) {
                            if (data && data.print_url) {
                                window.location.href = data.print_url;
                            } else {
                                window.location.href = storeBase;
                            }
                        });
                    }

                    // Opaque redirect"""

for view_file in views_to_update:
    if os.path.exists(view_file):
        with open(view_file, 'r', encoding='utf-8') as f:
            content = f.read()

        if "// Opaque redirect" in content and "actionValue === 'save_and_print'" not in content:
            content = content.replace("// Opaque redirect", injection)
            with open(view_file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Updated JS in {view_file}")
        else:
            print(f"Could not find or already updated JS in {view_file}")
    else:
        print(f"File {view_file} not found")
