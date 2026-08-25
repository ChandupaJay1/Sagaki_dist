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

for view_file in views_to_update:
    if os.path.exists(view_file):
        with open(view_file, 'r', encoding='utf-8') as f:
            content = f.read()

        # Find the form ID from nearby buttons
        form_id_match = re.search(r'form="([^"]+)"', content)
        form_id = form_id_match.group(1) if form_id_match else ""
        form_attr = f' form="{form_id}"' if form_id else ""

        # Replace type="button" with type="submit" form="..." name="action" value="save_and_print"
        # We need to be careful. The button could be:
        # <button type="button" class="btn btn-outline-secondary btn-sm me-1"><i class="ri-printer-line me-1"></i>Save & Print</button>
        # or similar.
        
        # We replace the exact button tag.
        new_content = re.sub(
            r'<button([^>]*)type=["\']button["\']([^>]*)>(.*?)Save & Print</button>',
            lambda m: f'<button{m.group(1)}type="submit"{form_attr} name="action" value="save_and_print"{m.group(2)}>{m.group(3)}Save & Print</button>',
            content,
            flags=re.IGNORECASE | re.DOTALL
        )
        
        with open(view_file, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated Button HTML in {view_file}")

