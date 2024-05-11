from json import dumps
from pathlib import Path
from re import sub
from os import remove
from _code_generation_helpers import get_text_content
ROOT = Path("05 Lean CLI/99 API Reference")
H3_INTRODUCTION = '01 Introduction.html'
H3_OPTIONS = '04 Options.html'
H3_COMMANDS = '05 Commands.html'

TYPE_CONVERSIONS = {
    "TEXT": "&lt;string&gt;",
    "INTEGER": "&lt;integer&gt;",
    "BOOLEAN": "&lt;boolean&gt;",
    "FILE": "&lt;file&gt;",
    "DIRECTORY": "&lt;directory&gt;",
    "RANGE": "&lt;range&gt;",
    "DECIMAL": "&lt;float&gt;",
    "FLOAT": "&lt;float&gt;",
}

def __get_commands_from_readme():
    commands = {}
    index = 0
    current_key = ''

    source = get_text_content(f'https://raw.githubusercontent.com/QuantConnect/lean-cli/master/README.md')

    for line in source.split('\n'):
        if line.startswith('###'):
            index += 1
            current_key = line[5:-1]
            commands[current_key] = [f'{index:02}']
            continue

        if current_key:
            commands[current_key].append(line)

    return {key: __convert_command(key, command) for key, command in commands.items() } 

def __convert_command(key, command):
    return {
        'metadata.json': dumps(
{
    "type": "metadata",
    "values": {
        "description": f"API reference for the command `{key}` in command line console.",
        "keywords": f"lean cli, api, api reference, command, command line, {key}",
        "og:description": f"API reference for the command `{key}` in command line console.",
        "og:title": f"{key} - Documentation QuantConnect.com",
        "og:type": "website",
        "og:site_name": f"{key} - QuantConnect.com",
        "og:image": f"https://cdn.quantconnect.com/docs/i/lean-cli/api-reference/{key.replace(' ','-')}.png"
    }
}, indent=4),
            
        H3_INTRODUCTION: f'''<p>{command[2]}</p>
<div class="cli section-example-container">
<pre>$ {command[5][7:]}</pre>
</div>''',

        H3_OPTIONS: __generate_options_table(key, command),

        H3_COMMANDS: __generate_commands_table(key, command),

        'index': command[0]
    }

def __generate_options_table(key, command):
    name = 'Options'
    i = next((i+1 for i, l in enumerate(command) if f'{name}:' in l), 0)
    if i < 1:
        return ''

    lines = []
    imax = command.index('```', i)
    imax = min(imax, next((i for i, l in enumerate(command) if f'Commands:' in l), imax))
    while i < imax:
        line = command[i].lstrip()
        if not line.startswith('-'):
            lines[-1][-1] += ' ' + line
            i += 1; continue
        parts = line.split('  ')
        if len(parts) == 1:
            i += 1
            parts += command[i].lstrip().split('  ')
        i += 1
        arg_and_type = parts[0].strip()
        if arg_and_type == '--help':
            lines.append(['--help', f"Display the help text of the <code>{key}</code> command and exit"])
            continue
        for raw, replacement in TYPE_CONVERSIONS.items():
            arg_and_type = arg_and_type.replace(raw, replacement)
        arg_and_type = sub(r"\[([^\]]+)\]", r"&lt;enum: \1&gt;", arg_and_type)
        arg_and_type = arg_and_type.replace("[", "&lt;").replace("]", "&gt;")
        lines.append([arg_and_type, parts[-1]])
    
    return __generate_table(name, key, lines)

def __generate_commands_table(key, command):
    name = 'Commands'
    i = next((i+1 for i, l in enumerate(command) if f'{name}:' in l), 0)
    if i < 1:
        return ''
    imax = command.index('```', i)    
    lines = [l.lstrip().split('  ') for l in command[i:imax]]
    return __generate_table("Arguments", key, lines, "expects")
    
def __generate_table(name, key, lines, verb='supports'):
    def remove_period(line):
        line = line.strip()
        if line[-1] == '.':
            line = line[:-1]
        return line

    rows = ''.join([f'''
<tr>
    <td nowrap><code>{line[0]}</code></td>
    <td>{remove_period(line[-1])}</td>
</tr>''' for line in lines])
    
    return f'''<p>The <code>{key}</code> command {verb} the following {name.lower()}:</p>
<table class="table qc-table">
    <thead>
        <tr>
            <th>{name[:-1]}</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>{rows}
    </tbody>
</table>'''

if __name__ == '__main__':
    commands = __get_commands_from_readme()
    directories = {x.name[3:] : x for x in ROOT.iterdir() if x.is_dir()}

    for key, command in commands.items():
        dir = directories.get(key)
        index = command.pop('index')
        if not dir:
            directories[key] = ROOT.joinpath(f'{index} {key}')
            directories[key].mkdir(exist_ok=True, parents=True)
            continue
        # Rename directories to accomodate new commands
        if index != dir.name[:2]:
            dir = dir.rename(f'{ROOT}/{index} {key}')
            directories[key] = dir
        
    for key, command in commands.items():
        dir = directories.get(key)
        [remove(f.absolute()) for f in dir.iterdir()
            if 'options' in f.name.lower() or 'commands' in f.name.lower()]
        for filename, content in command.items():
            if content:
                with dir.joinpath(filename).open('w', encoding='utf-8') as fp:
                    fp.write(content)

    # Check for directories without a command
    for path in ROOT.iterdir():
        if path.name[3:] not in commands and path.is_dir():
            print(f'Remove page with no command: {path}')