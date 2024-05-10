from json import dump
from pathlib import Path
from _code_generation_helpers import get_text_content, List
ROOT = Path("05 Lean CLI/99 API Reference")

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

    return commands

def __seed_new_command(key: str, command: List[str]) -> None:
    dir = ROOT.joinpath(f'{command[0]} {key}')
    dir.mkdir(exist_ok=True, parents=True)
        
    usage = command[5][7:]
        
    with dir.joinpath("01 Introduction.html").open('w', encoding='utf-8') as fp:
        fp.write(f'''<p>{command[2]}</p>
<div class="cli section-example-container">
<pre>$ {usage}</pre>
</div>''')

    rows = ''
    start = 1 + command.index('Options:')
    stop = command.index('```', start)
    if start > 0:
        for i in range(start, stop):
            parts = command[i].lstrip().split('  ')
            if len(parts) == 1:
                i += 1
                parts += command[i].lstrip().split('  ')
            i += 1
            rows += f'''
<tr>
    <td nowrap><code>{parts[0].strip()}</code></td>
    <td>{parts[-1].strip()}</td>
</tr>'''

    with dir.joinpath("03 Options.html").open('w', encoding='utf-8') as fp:
        if rows:
            fp.write(f'''<p>The <code>{key}</code> command supports the following options:</p>
<table class="table qc-table">
    <thead>
        <tr>
            <th>Option</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>{rows}
    </tbody>
</table>''')

    dump({
    "type": "metadata",
    "values": {
        "description": f"API reference for the command `{key}` in command line console.",
        "keywords": f"lean cli, api, api reference, command, command line, {key}",
        "og:description": f"API reference for the command `{key}` in command line console.",
        "og:title": f"{key} - Documentation QuantConnect.com",
        "og:type": "website",
        "og:site_name": "{key} - QuantConnect.com",
        "og:image": f"https://cdn.quantconnect.com/docs/i/lean-cli/api-reference/{key.replace(' ','-')}.png"
    }
}, dir.joinpath("metadata.json").open('w'), indent = 4)

    return dir

def __check_for_missing_options(dir: Path, command: List[str]) -> None:
    files = [f for f in dir.iterdir() if f.name.endswith('Options.html')]
    if not files:
        return

    tbody = 0
    descriptions = {}
    with files[0].open("r", encoding="utf-8") as fp:
        lines = fp.readlines()
        for i, line in enumerate(lines):
            if '<tbody>' in line:
                tbody = i + 1
            start = line.find('--', line.find('<td nowrap'))
            if start < 0:
                start = line.find('-', line.find('<td nowrap'))
            if start < 0:    
                continue
            end = next(start + f for f, v in enumerate(line[start:]) if v in [' ', '<'])
            descriptions[line[start:end]] = lines[i-1:i+3]

    if not descriptions:
        return

    start = 1 + command.index('Options:')
    stop = command.index('```', start)

    lines = lines[:tbody]

    for i in range(start, stop):
        # First 5 to avoid comments
        key = next((x for x in command[i].split(' ')[:5] if x.startswith('--')), None)
        if key:
            lines.extend(descriptions.pop(key, [f'''        <tr>
            <td nowrap><code>{key}</code></td>
            <td></td>
        </tr>
''']))

    with files[0].open("w") as fp:
        fp.writelines(lines + ['    </tbody>\n</table>'])

if __name__ == '__main__':
    commands = __get_commands_from_readme()
    directories = {x.name[3:] : x for x in ROOT.iterdir() if x.is_dir()}

    for key, command in commands.items():
        dir = directories.get(key)
        if not dir:
            directories[key] = __seed_new_command(key, command)
            continue
        # Rename directories to accomodate new commands
        if command[0] != dir.name[:2]:
            dir = dir.rename(f'{ROOT}/{command[0]} {key}')
            directories[key] = dir

        __check_for_missing_options(dir, command)

    # Check for directories without a command
    for path in ROOT.iterdir():
        if path.name[3:] not in commands and path.is_dir():
            print(f'Remove page with no command: {path}')
