import os
from datetime import datetime as dt
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
from _code_generation_helpers import get_lean_cli_commands

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
        'lean login', 'lean init', 'lean whoami', 'lean logout'
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
    'Results': [
        'lean logs', 'lean report'
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

def _generate_lean_cli_cheat_sheet(location):
    """Generate a 1200x630 LEAN CLI API cheat sheet image."""
    width, height = 1200, 630
    bg_color = (255, 255, 255, 255)
    title_color = (30, 30, 30, 255)
    heading_color = (0, 120, 200, 255)
    cmd_color = (30, 30, 30, 255)
    desc_color = (100, 100, 100, 255)
    accent_color = (0, 120, 200, 255)

    font_path = f'{location}Inter-Bold.ttf'
    title_font = ImageFont.FreeTypeFont(font_path, 26)
    heading_font = ImageFont.FreeTypeFont(font_path, 12)
    cmd_font = ImageFont.FreeTypeFont(font_path, 10)
    desc_font = ImageFont.truetype(font_path, 9)

    # Fetch descriptions from LEAN CLI README
    all_commands = get_lean_cli_commands()
    for group, cmds in LEAN_CLI_COMMAND_GROUPS.items():
        for cmd in cmds:
            if cmd not in all_commands:
                print(f'Warning: "{cmd}" in group "{group}" not found in LEAN CLI README')

    image = Image.new('RGBA', (width, height), bg_color)
    draw = ImageDraw.Draw(image)

    # Title
    draw.text((40, 20), 'LEAN CLI API Cheat Sheet', fill=title_color, font=title_font)
    draw.rectangle([(40, 52), (300, 54)], fill=accent_color)

    # Layout: 3 columns
    col_x = [40, 420, 800]
    groups = list(LEAN_CLI_COMMAND_GROUPS.items())

    # Distribute groups across 3 columns
    # Column 0: Authentication, Configuration, Projects, Data
    # Column 1: Research, Backtesting, Live Trading
    # Column 2: Results (remaining groups can be added here)
    columns = [
        groups[0:4],
        groups[4:7],
        groups[7:],
    ]

    line_height = 14

    for col_idx, col_groups in enumerate(columns):
        x = col_x[col_idx]
        y = 70

        for group_name, commands in col_groups:
            # Group heading
            draw.text((x, y), group_name.upper(), fill=heading_color, font=heading_font)
            y += 18

            for cmd in commands:
                # Command name (strip "lean " prefix for brevity)
                short_cmd = cmd[5:]  # remove "lean "
                desc = all_commands.get(cmd, '')
                draw.text((x + 8, y), short_cmd, fill=cmd_color, font=cmd_font)
                # Description after a dash
                if desc:
                    cmd_width = draw.textlength(short_cmd, font=cmd_font)
                    draw.text((x + 8 + cmd_width + 6, y + 1), f'— {desc}', fill=desc_color, font=desc_font)
                y += line_height

            y += 10

    # Footer
    draw.text((40, height - 25), 'quantconnect.com/docs/v2/lean-cli', fill=(150, 150, 150, 255), font=desc_font)

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