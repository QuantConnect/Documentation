import os
import sys
from datetime import datetime as dt
from importlib import import_module
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont

PRODUCTS = [
    '01 Cloud Platform',
    '02 Local Platform',
    '03 Writing Algorithms',
    '04 Research Environment',
    '05 Lean CLI',
    '06 LEAN Engine'
]

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

CHEAT_SHEET_CATEGORIES = [
    ('Authentication', [
        'lean login', 'lean init', 'lean whoami', 'lean logout']),
    ('Configuration', [
        'lean config list', 'lean config get', 'lean config set', 'lean config unset']),
    ('Projects', [
        'lean project-create', 'lean project-delete', 'lean cloud pull', 'lean cloud push',
        'lean library add', 'lean library remove']),
    ('Data', [
        'lean data download', 'lean data generate']),
    ('Research', [
        'lean research']),
    ('Backtests', [
        'lean backtest', 'lean cloud backtest', 'lean optimize', 'lean cloud optimize']),
    ('Live Trading', [
        'lean live', 'lean live liquidate', 'lean live stop',
        'lean cloud live', 'lean cloud live liquidate', 'lean cloud live stop',
        'lean cloud status']),
    ('Algorithm Results', [
        'lean logs', 'lean report']),
]

def generate_cheat_sheet(location):
    sys.path.insert(0, 'code-generators')
    cli_generator = import_module('Lean-CLI-API-Reference-Code-Generator')
    raw_commands = cli_generator.get_raw_commands_from_readme()

    WIDTH, MARGIN = 1200, 50
    COL_GAP = 40
    col_width = (WIDTH - 2 * MARGIN - COL_GAP) // 2

    font_path = f'{location}Inter-Bold.ttf'
    title_font = ImageFont.truetype(font_path, 36)
    category_font = ImageFont.truetype(font_path, 20)
    command_font = ImageFont.truetype(font_path, 14)

    BG_COLOR = (247, 247, 246)
    TITLE_COLOR = (30, 30, 30)
    CATEGORY_COLOR = (109, 60, 209)
    COMMAND_COLOR = (50, 50, 50)
    DESC_COLOR = (120, 120, 120)
    SEPARATOR_COLOR = (220, 220, 218)

    descriptions = {}
    for name, lines in raw_commands.items():
        desc = lines[2] if len(lines) > 2 else ''
        if desc.endswith('.'):
            desc = desc[:-1]
        descriptions[name] = desc

    categories = []
    for cat_name, cmd_names in CHEAT_SHEET_CATEGORIES:
        cmds = []
        for cmd in cmd_names:
            if cmd in descriptions:
                cmds.append((cmd, descriptions[cmd]))
        if cmds:
            categories.append((cat_name, cmds))

    # Split categories into two columns by total line count
    left, right = [], []
    left_lines, right_lines = 0, 0
    for cat_name, cmds in categories:
        count = 1 + len(cmds)
        if left_lines <= right_lines:
            left.append((cat_name, cmds))
            left_lines += count
        else:
            right.append((cat_name, cmds))
            right_lines += count

    LINE_H, CAT_GAP, CMD_GAP = 22, 30, 4
    HEADER_H = 100

    def col_height(col):
        h = 0
        for i, (_, cmds) in enumerate(col):
            if i > 0:
                h += CAT_GAP
            h += 30
            h += len(cmds) * (LINE_H + CMD_GAP)
        return h

    content_h = max(col_height(left), col_height(right))
    HEIGHT = HEADER_H + content_h + MARGIN + 40

    image = Image.new('RGBA', (WIDTH, HEIGHT), BG_COLOR)
    draw = ImageDraw.Draw(image)

    draw.text((MARGIN, MARGIN), "LEAN CLI CHEAT SHEET", fill=TITLE_COLOR, font=title_font)
    draw.line([(MARGIN, HEADER_H - 10), (WIDTH - MARGIN, HEADER_H - 10)], fill=SEPARATOR_COLOR, width=2)

    def truncate_text(text, font, max_width):
        if font.getlength(text) <= max_width:
            return text
        while font.getlength(text + '...') > max_width and len(text) > 0:
            text = text[:-1]
        return text.rstrip() + '...'

    def draw_column(col, x_start, y_start):
        y = y_start
        for i, (cat_name, cmds) in enumerate(col):
            if i > 0:
                y += CAT_GAP
            draw.text((x_start, y), cat_name.upper(), fill=CATEGORY_COLOR, font=category_font)
            y += 30
            for cmd, desc in cmds:
                cmd_text = f"{cmd}  —  "
                cmd_w = command_font.getlength(cmd_text)
                max_desc_w = col_width - cmd_w
                desc_truncated = truncate_text(desc, command_font, max_desc_w)
                draw.text((x_start, y), cmd_text, fill=COMMAND_COLOR, font=command_font)
                draw.text((x_start + cmd_w, y), desc_truncated, fill=DESC_COLOR, font=command_font)
                y += LINE_H + CMD_GAP
        return y

    y_start = HEADER_H
    draw_column(left, MARGIN, y_start)
    draw_column(right, MARGIN + col_width + COL_GAP, y_start)

    footer_y = HEIGHT - 30
    draw.text((MARGIN, footer_y), "quantconnect.com", fill=DESC_COLOR, font=command_font)

    output = Path(f'{location}lean-cli-cheat-sheet.png')
    image.save(str(output))
    image.close()
    print(f'Cheat sheet generated: {output}')

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

    generate_cheat_sheet(location)
    counter += 1

    print(f'{counter} images generated in {dt.now()-now}')
    if missing_metadata:
        print(f"Found {len(missing_metadata)} missing metatdata.json files in: [" + "\n" + "\n".join(str(x) for x in missing_metadata) + "\n]")