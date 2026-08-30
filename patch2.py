with open('resources/views/pay_bills/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(r'if(source\.input) source\.input', 'if(source.input) source.input')
content = content.replace(r'if(source\.hidden) source\.hidden', 'if(source.hidden) source.hidden')
content = content.replace(r'if(source\.cell) source\.cell', 'if(source.cell) source.cell')
content = content.replace(r'if(this) this.innerHTML = \'<i class=\"ri-check-line', r'this.innerHTML = \'<i class=\"ri-check-line')
content = content.replace(r'if(this) this.innerHTML = originalHtml', r'this.innerHTML = originalHtml')


with open('resources/views/pay_bills/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
