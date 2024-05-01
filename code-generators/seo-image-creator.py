import os
from datetime import datetime as dt
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
            if dir == product or 'metadata.json' not in files:
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

    print(f'{counter} images generated in {dt.now()-now}')
    if missing_metadata:
        print(f"Found {len(missing_metadata)} missing metatdata.json files in: [" + "\n" + "\n".join(str(x) for x in missing_metadata) + "\n]")