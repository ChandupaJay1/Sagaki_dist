import os
import re

# View updates
views_to_update = [
    'resources/views/sales_returns/create.blade.php',
    'resources/views/sales_orders/create.blade.php',
    'resources/views/purchase_orders/create.blade.php',
    'resources/views/inventory_transfers/create.blade.php',
    'resources/views/grn_returns/create.blade.php',
    'resources/views/grns/create.blade.php'
]

# 1. Update Views
for view_file in views_to_update:
    if os.path.exists(view_file):
        with open(view_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        content = re.sub(
            r'<button([^>]*)type=["\']button["\']([^>]*)>([^<]*?)Save & Print</button>',
            r'<button\1type="submit" name="action" value="save_and_print"\2>\3Save & Print</button>',
            content,
            flags=re.IGNORECASE
        )
        
        with open(view_file, 'w', encoding='utf-8') as f:
            f.write(content)

print("Views updated.")
