from pathlib import Path

root_dir = "Resources/datasets/market-hours/"
target_dir = "02 Writing Algorithms/44 Market Hours/"
conversions = {
    "future": "01 Futures",
    "cfd": "03 CFDs"
}

for dir, target in conversions.items():
    path = Path(f'{root_dir}{dir}')
    subdirs = [str(subdir.name).upper() for subdir in path.iterdir() if subdir.is_dir()]
    
    i = 1
    
    for subdir in subdirs:
        market_dir = path / subdir.lower()
        output_dir = Path(f'{target_dir}{target}/{i:02} {subdir}')
        output_dir.mkdir(parents=True, exist_ok=True)
        
        j = 1

        for html in (market_dir / 'generic').glob('**/*.html'):
            page_name = str(html.name).replace('-', ' ').upper()
            
            codes = open(html).read()
            with open(output_dir / f'{j:02} {page_name.replace("Html", "html")}', 'w', encoding='utf-8') as html_file:
                html_file.write(codes)
            
            j += 1
            
        codes = open(f'{root_dir}{dir.upper()}.html').read().split(f'<h4>{subdir.upper()}</h4>')[-1].split('</table>')[0].split('</h4>')[-1] + '</table>'
        with open(output_dir / f'{j:02} Specific Assets.html', 'w', encoding='utf-8') as html_file:
            html_file.write("<p>Below table shows specific symbols with their special market hours.</p>\n")
            html_file.write(codes.replace(f'<tr><td><a href="{subdir.lower()}">Generic</a></td></tr>', '').replace(f'<td><a href="{subdir.lower()}">Generic</a></td>', ''))
            
        assets_subdirs = [str(subdir.name).upper() for subdir in market_dir.iterdir() if subdir.is_dir() and subdir.name != "generic"]
        
        k = 1
        
        for asset_page in assets_subdirs:
            asset_dir = market_dir / asset_page.lower()
            asset_page_dir = output_dir/ f'{k:02} {asset_page}'
            asset_page_dir.mkdir(parents=True, exist_ok=True)
            
            n = 1
            
            for html in asset_dir.glob('**/*.html'):
                page_name = str(html.name).replace('-', ' ').upper()

                codes = open(html).read()
                with open(asset_page_dir / f'{n:02} {page_name.replace("Html", "html")}', 'w', encoding='utf-8') as html_file:
                    html_file.write(codes)
                
                n += 1
                
            k += 1
            
        i += 1