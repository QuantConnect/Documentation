from os import popen

CMD = {
    'csharp': "dotnet list ../Lean/QuantConnect.Lean.sln package",
    'python': 'docker run --entrypoint bash quantconnect/lean:latest -c "conda list"'
}

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

    filename = 'Resources\libraries\supported-libraries.php'

    with open(filename, mode='a', encoding='utf-8') as fp:
        fp.write(f'<pre class="{language}">')
        for key, value in sorted(libraries.items()):
            count = maxlen - len(key)
            fp.write(f'{key + " " * count} {value}\n')
        fp.write('</pre>')

    print(f'{filename} file written with {len(libraries)-1} entries')