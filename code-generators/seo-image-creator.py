import os
from datetime import datetime as dt
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
from urllib.request import urlopen

PRODUCTS = [
    '01 Cloud Platform',
    '02 Local Platform',
    '03 Writing Algorithms',
    '04 Research Environment',
    '05 Lean CLI',
    '06 LEAN Engine'
]

LEAN_CLI_COMMAND_GROUPS = {
    'Authentication': [
        'lean login', 'lean logout', 'lean init', 'lean whoami'
    ],
    'Configuration': [
        'lean config list', 'lean config get',
        'lean config set', 'lean config unset'
    ],
    'Projects': [
        'lean create-project', 'lean delete-project',
        'lean cloud pull', 'lean cloud push',
        'lean library add', 'lean library remove'
    ],
    'Data': [
        'lean data download', 'lean data generate'
    ],
    'Research': [
        'lean research'
    ],
    'Backtesting': [
        'lean backtest', 'lean cloud backtest',
        'lean optimize', 'lean cloud optimize'
    ],
    'Live Trading': [
        'lean live deploy', 'lean live stop',
        'lean live liquidate',
        'lean cloud live deploy', 'lean cloud live stop',
        'lean cloud live liquidate', 'lean cloud status'
    ],
    'Object Store': [
        'lean object-store get', 'lean object-store set',
        'lean object-store list', 'lean object-store delete',
        'lean cloud object-store get', 'lean cloud object-store set',
        'lean cloud object-store list', 'lean cloud object-store delete'
    ],
    'Results': [
        'lean logs', 'lean report'
    ],
    'Security': [
        'lean encrypt', 'lean decrypt'
    ],
}

class ImageGenerator:
    def __init__(self, fonts: list, template: str):
        self.canvas = Image.open(template)
        self.fonts = fonts
        self.x = 63
        self.y = 256
        self.max_text_width = self.canvas.size[0]-self.x*2

    def AddTextToImage(self, lines, outputfile):
        font = self.fonts[max(0, len(lines) - 4)]
        dx, dy = .8 * font.size, 1.2 * font.size

        image = self.canvas.copy()
        I1 = ImageDraw.Draw(image)
        for i, line in enumerate(lines):
            xy = (self.x + i * dx, self.y + i * dy)
            I1.text(xy, line, fill='#000', font=font)
        image.save(f'{outputfile}.png')
        image.close()

def _get_lean_cli_commands():
    """Fetch current LEAN CLI commands from the repository README."""
    source = urlopen('https://raw.githubusercontent.com/QuantConnect/lean-cli/master/README.md').read().decode('utf-8')
    commands = set()
    for line in source.split('\n'):
        if line.startswith('### '):
            commands.add(line[5:-1])
    return commands

def _generate_lean_cli_cheat_sheet(location):
    """Generate a 1200x630 LEAN CLI API cheat sheet image."""
    width, height = 1200, 630
    bg_color = (25, 25, 35, 255)
    title_color = (255, 255, 255, 255)
    heading_color = (100, 200, 255, 255)
    command_color = (200, 200, 210, 255)
    accent_color = (100, 200, 255, 255)

    font_path = f'{location}Inter-Bold.ttf'
    title_font = ImageFont.FreeTypeFont(font_path, 28)
    heading_font = ImageFont.FreeTypeFont(font_path, 14)
    command_font = ImageFont.FreeTypeFont(font_path, 12)

    # Validate groups against live README
    live_commands = _get_lean_cli_commands()
    for group, cmds in LEAN_CLI_COMMAND_GROUPS.items():
        for cmd in cmds:
            if cmd not in live_commands:
                print(f'Warning: "{cmd}" in group "{group}" not found in LEAN CLI README')

    image = Image.new('RGBA', (width, height), bg_color)
    draw = ImageDraw.Draw(image)

    # Title
    draw.text((40, 25), 'LEAN CLI  API Cheat Sheet', fill=title_color, font=title_font)
    # Accent line under title
    draw.rectangle([(40, 62), (340, 64)], fill=accent_color)

    # Layout: 3 columns
    col_x = [40, 420, 800]
    groups = list(LEAN_CLI_COMMAND_GROUPS.items())

    # Distribute groups across 3 columns
    # Column 0: Authentication, Configuration, Projects, Data
    # Column 1: Research, Backtesting, Live Trading
    # Column 2: Object Store, Results, Security
    columns = [
        groups[0:4],
        groups[4:7],
        groups[7:10],
    ]

    for col_idx, col_groups in enumerate(columns):
        x = col_x[col_idx]
        y = 85

        for group_name, commands in col_groups:
            # Group heading
            draw.text((x, y), group_name.upper(), fill=heading_color, font=heading_font)
            y += 22

            for cmd in commands:
                draw.text((x + 10, y), cmd, fill=command_color, font=command_font)
                y += 17

            y += 12

    # Footer
    draw.text((40, height - 30), 'quantconnect.com/docs/v2/lean-cli', fill=(120, 120, 140, 255), font=command_font)

    output_path = f'{location}lean-cli.png'
    image.save(output_path)
    image.close()
    print(f'LEAN CLI cheat sheet saved to {output_path}')

if __name__ == '__main__':
    counter = 0
    now = dt.now()
    location = f"Resources/social/"
    fonts = [ ImageFont.FreeTypeFont(f'{location}Inter-Bold.ttf', size) for size in [ 62, 50, 42 ] ]
    missing_metadata = []

    for product in PRODUCTS:
        name = '-'.join(product.lower().split(' ')[1:])
        template = f"{location}docs-meta-{name}.png"
        if not os.path.exists(template):
            continue

        image_generator = ImageGenerator(fonts, template)
        for dir, subdirs, files in os.walk(product):
            if 'metadata.json' not in files:
                # metadata file should only be a must in the tree-leaf dir
                if not subdirs:
                    missing_metadata.append(dir)
                continue

            lines = [' '.join(line.split(' ')[1:]) for line in dir.split(os.path.sep)]

            outputfile = '/'.join([line.lower().replace(' ','-') for line in lines])
            url = f'https://cdn.quantconnect.com/docs/i/{outputfile}.png'
            outputfile = Path(location + outputfile)
            outputfile.parent.mkdir(parents=True, exist_ok=True)

            image_generator.AddTextToImage(lines[1:], outputfile)
            counter += 1

            with open(f"{dir}/metadata.json", mode='r') as fp:
                lines = fp.readlines()
                line = next((x for x in lines if "og:image" in x), None)
                if not line:
                    continue
                end = line.index('og:image')-1
                lines[lines.index(line)] = f'{line[:end]}"og:image": "{url}"\n'

            with open(f"{dir}/metadata.json", mode='w') as fp:
                fp.writelines(lines)

    # Generate LEAN CLI cheat sheet after all SEO images (overwrites lean-cli.png)
    _generate_lean_cli_cheat_sheet(location)

    print(f'{counter} images generated in {dt.now()-now}')
    if missing_metadata:
        print(f"Found {len(missing_metadata)} missing metatdata.json files in: [" + "\n" + "\n".join(str(x) for x in missing_metadata) + "\n]")