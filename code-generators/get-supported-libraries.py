from os import popen

CMD = {
    'csharp': "dotnet list package",
    'python': "conda list"
}
filename = 'Resources/libraries/supported-libraries.php'
code = ""

for language, cmd in CMD.items():
    print(cmd)
    content = popen(cmd)
    maxlen, libraries = 0, {'# Name': 'Version'}

    for i, line in enumerate(content.readlines()):
        if i < 3: continue
        line = [x for x in line.split(' ') if x != '']
        
        if language == "csharp":
            if ">" not in line: continue
            
            if line[-1] != "\n":
                key, value = [line[1]] + [line[-1].replace("\n", "")]
            else:
                key, value = [line[1]] + [line[-2].replace("\n", "")]
        else:
            key, value = line[:2]
        maxlen = max(maxlen, len(key))
        libraries[key] = value
        
    code += f"<pre class=\"{language}\">"
    for key, value in sorted(libraries.items()):
        count = maxlen - len(key)
        code += f'{key + " " * count} {value}\n'
    code += "</pre>\n"
"""
with open(filename, mode='w', encoding='utf-8') as fp:
    fp.write(f'<div class="section-example-container">\n{code}</div>')
"""
print(code)