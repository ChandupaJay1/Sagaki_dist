import re

with open('resources/views/pay_bills/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. document.getElementById('something').value = 
content = re.sub(r'document\.getElementById\((.*?)\)\.value\s*=\s*(.*?);', r'(() => { let el = document.getElementById(\1); if(el) el.value = \2; })();', content)

# 2. document.getElementById('something').textContent =
content = re.sub(r'document\.getElementById\((.*?)\)\.textContent\s*=\s*(.*?);', r'(() => { let el = document.getElementById(\1); if(el) el.textContent = \2; })();', content)

# 3. Handle variables
elements = ['displayAmountInput', 'lkrTotalAmountInput', 'entityBalanceInput', 'chequeNoInput', 'pdChequeDateInput', 
            'useInput', 'amountUsedHidden', 'creditHidden', 'payInput', 'hiddenInput', 'input', 'amtDueCell', 'remainingCell', 
            'billsTableBody', 'creditsTableBody', 'source\.input', 'source\.hidden', 'source\.cell', 'this', 'availableCreditSpan', 'creditCountSpan', 'appliedCreditsCount', 'appliedCreditsSummary']

for el in elements:
    # el.value = ...
    content = re.sub(rf'^(\s*){el}\.value\s*=\s*(.*?);', rf'\1if({el}) {el}.value = \2;', content, flags=re.MULTILINE)
    # el.textContent = ...
    content = re.sub(rf'^(\s*){el}\.textContent\s*=\s*(.*?);', rf'\1if({el}) {el}.textContent = \2;', content, flags=re.MULTILINE)
    # el.innerHTML = ...
    content = re.sub(rf'^(\s*){el}\.innerHTML\s*=\s*(.*?);', rf'\1if({el}) {el}.innerHTML = \2;', content, flags=re.MULTILINE)
    # el.style.xyz = ...
    content = re.sub(rf'^(\s*){el}\.style\.([a-zA-Z0-9_]+)\s*=\s*(.*?);', rf'\1if({el}) {el}.style.\2 = \3;', content, flags=re.MULTILINE)
    # el.classList...
    content = re.sub(rf'^(\s*){el}\.classList(.*);', rf'\1if({el}) {el}.classList\2;', content, flags=re.MULTILINE)

# Also fix the previous manual fixes to avoid double if's
content = content.replace("if(entityBalanceInput) if(entityBalanceInput)", "if(entityBalanceInput)")
content = content.replace("if(availableCreditSpan) if(availableCreditSpan)", "if(availableCreditSpan)")
content = content.replace("if(creditCountSpan) if(creditCountSpan)", "if(creditCountSpan)")
content = content.replace("if(billsTableBody) if(billsTableBody)", "if(billsTableBody)")

with open('resources/views/pay_bills/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
