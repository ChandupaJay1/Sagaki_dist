import re
import os

files = [
    ('app/Http/Controllers/SalesOrderController.php', 'sales-orders', 'order'),
    ('app/Http/Controllers/SalesReturnController.php', 'sales-returns', 'return'),
    ('app/Http/Controllers/PurchaseOrderController.php', 'purchase-orders', 'order'),
    ('app/Http/Controllers/GrnController.php', 'grns', 'grn'),
    ('app/Http/Controllers/GrnReturnController.php', 'grn-returns', 'return'),
    ('app/Http/Controllers/InventoryTransferController.php', 'inventory-transfers', 'transfer')
]

for file, route, view in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Change redirect
    content = re.sub(r"('print_url'\s*=>\s*route\(')" + route.replace('-', '_') + r"\.show'", r"\1" + route + r".print'", content)
    content = re.sub(r"('print_url'\s*=>\s*route\(')" + route + r"\.show'", r"\1" + route + r".print'", content)
    
    content = re.sub(r"return redirect\(\)->route\('" + route.replace('-', '_') + r"\.show'", r"return redirect()->route('" + route + r".print'", content)
    content = re.sub(r"return redirect\(\)->route\('" + route + r"\.show'", r"return redirect()->route('" + route + r".print'", content)

    # Add print method if not exists
    if 'public function print(' not in content:
        match = re.search(r'public function show\([^)]+\)\s*\{(.*?)\}', content, re.DOTALL)
        if match:
            show_body = match.group(1)
            # Find the view string
            print_body = re.sub(r"view\((['\"])[^'\"]+\.show", r"view(\1" + route.replace('-', '_') + ".print", show_body)
            print_method = "\n    public function print()\n    {" + print_body + "}\n"
            
            last_brace = content.rfind('}')
            if last_brace != -1:
                content = content[:last_brace] + print_method + "}\n"
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

# Invoice Controller
with open('app/Http/Controllers/InvoiceController.php', 'r', encoding='utf-8') as f:
    content = f.read()
content = re.sub(r"('print_url'\s*=>\s*route\('invoices)\.show'", r"\1.print'", content)
content = re.sub(r"return redirect\(\)->route\('invoices\.show'", r"return redirect()->route('invoices.print'", content)
with open('app/Http/Controllers/InvoiceController.php', 'w', encoding='utf-8') as f:
    f.write(content)

# PayBill Controller
with open('app/Http/Controllers/PayBillController.php', 'r', encoding='utf-8') as f:
    content = f.read()
content = re.sub(r"('print_url'\s*=>\s*route\('pay-bills)\.show'", r"\1.print'", content)
content = re.sub(r"return redirect\(\)->route\('pay-bills\.show'", r"return redirect()->route('pay-bills.print'", content)
with open('app/Http/Controllers/PayBillController.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Done")
