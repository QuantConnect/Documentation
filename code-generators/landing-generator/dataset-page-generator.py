SOURCE = "https://s3.amazonaws.com/cdn.quantconnect.com/web/docs/alternative-data-dump-v2021-12-06.json"
CSS_FILE = "html_style.css"
DATASET_PAGE_DIR = "dataset-page"
SECTIONS = {
    "Introduction": {
        "h1_style": None,
        "alternative_name": "About {dataset['name']}",
        "additional_content": None
        }, 
    "About the Provider": {
        "h1_style": None,
        "alternative_name": "About {dataset['vendorName']}",
        "additional_content": """<div class="content-cta">
                <div class="logo-holder cta-logo">
                    <img src="{dataset['datasetImageLight'] if dataset['datasetImageLight'] else dataset['vendorImageLight']}" alt="">
                </div>
                <div class="cta-content">
                    <h1>Add {dataset['name']}</h1>
                    <a href="https://www.quantconnect.com{dataset['url']}/pricing" class="btn btn-primary">Add Dataset</a>
                    <a href="https://www.quantconnect.com/signup" class="btn btn-secondary">Create Free QuantConnect Account</a>
                </div>
            </div>"""
        }, 
    "About QuantConnect": {
        "h1_style": None,
        "alternative_name": None,
        "additional_content": """<p>QuantConnect was founded in 2012 to serve quants everywhere with the best possible algorithmic trading technology. Seeking to disrupt a notoriously closed-source industry, QuantConnect takes a radically open-source approach to algorithmic trading. Through the QuantConnect web platform, more than 50,000 quants are served every month.</p>"""
        }, 
    "Algorithm Example": {
        "h1_style": 'id="algo-example" class="section"',
        "alternative_name": None,
        "additional_content": """<div class="code-example" style="background-color: #F7F9FC;border: 1px solid #D9E1EB;border-radius: 3px;overflow-x: auto;margin-top: 2rem;padding: 2rem;">

<!-- code snippet start -->

{highlight([x for x in dataset['examples'] if 'title' in x and 'Classic Algorithm' in x['title']][0]['content'].split('<code class="language-python">')[-1].split('</code>')[0], PythonLexer(), style)}
<style id="css-style">{css}</style>
                
<!-- code snippet end -->


</div>"""
        }, 
    "Example Applications": {
        "h1_style": 'id="example-applications" class="section"',
        "alternative_name": None,
        "additional_content": None
        }, 
    "Pricing": {
        "h1_style": 'id="pricing" class="section"',
        "alternative_name": None,
        "additional_content": """<div class="pricing-cards" style="margin-top:2rem;">
            {line_breaker_char.join(['<div class="pricing-card"><div class="pricing-content"><div class="title"><h2>' + product['shortDescription'] + '</h2>' + product['description'].split('</p>')[0] + '</p></div><div class="options">' + product['description'].split('</p>')[1].replace('<ul>', '<ul style="margin-bottom:2rem;">') + '</div></div><div class="pricing"><h4 style="color:#8F9CA3;margin-bottom:0.5rem">PRICE</h4><h2>' + product['price']['priceCTA'] + '</h2><a href="https://www.quantconnect.com' + dataset['url'] + '/cli" class="btn btn-primary btn-pricing btn-cli"><span></span>LEAN CLI</a></div></div>' if product['cliProduct'] else '<div class="pricing-card"><div class="pricing-content"><div class="title"><h2>' + product['shortDescription'] + '</h2>' + product['description'].split('</p>')[0] + '</p></div><div class="options">' + product['description'].split('</p>')[1].replace('<ul>', '<ul style="margin-bottom:2rem;">') + '</div></div><div class="pricing"><h4 style="color:#8F9CA3;margin-bottom:0.5rem">PRICE</h4><h2>' + product['price']['priceCTA'] + '</h2><a href="https://www.quantconnect.com' + dataset['url'] + '/documentation" class="btn btn-primary btn-pricing">Documentation</a></div></div>' for product in sorted(dataset['products'], key=lambda x: x['cloudProduct'], reverse=True)])}
            </div>""" }
    }

# ----------------------------------------------------------------------------------------------------------

from pathlib import Path
import random
from urllib.request import urlopen

from pygments import highlight
from pygments.lexers import PythonLexer
from pygments.formatters import HtmlFormatter
from CodeStyle import CodeStyle

def CreateSection(section: str, dataset: dict, source: dict) -> str:
    h1_style = source['h1_style']
    alternative_name = source['alternative_name']
    additional_content = source['additional_content']
    
    header = section if not alternative_name else eval("f'''"+alternative_name.replace("'", '"')+"'''")
    h1 = f' {h1_style}' if h1_style else ''
    try:
        content = [x for x in \
            [item for sublist in dataset.values() if isinstance(sublist, list) for item in sublist]\
            if 'title' in x and x['title'] == section][0]['content']\
            .replace(backslash_char, '')\
            .split('</ul>')[0]
    except:
        content = ""

    code = f"""
            <h1{h1}>{header}</h1>
            {content}
            """
            
    if additional_content:
        code += eval("f'''" + additional_content + "'''")
    
    code += """
<hr>"""

    return code

response = urlopen(SOURCE).read().decode("utf-8") if 'http' in SOURCE else open(SOURCE).read()
response = response.replace('false', 'False').replace(': true', ': True').replace('null', 'None')
doc = eval(response)

css_code = open(CSS_FILE).read()

destination_folder = Path(DATASET_PAGE_DIR)
destination_folder.mkdir(parents=True, exist_ok=True)

style = HtmlFormatter(style=CodeStyle, cssclass="demo-highlight")
css = style.get_style_defs('.demo-highlight')

backslash_char = "\\"
line_breaker_char = "\n"

for dataset in doc:
    samples = random.sample([d for d in doc if d != dataset], 3)
    
    stripes = """.stripes {
    display: flex;
}"""
    new_stripes = f""".stripes {{
    display: flex;
}}

.stripes>div {{
    height: 0.5rem;
    background-color: #{dataset['headerColor']};
}}"""
    
    html = f"""<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{dataset['name']}</title>
    <style>
    {css_code.replace(stripes, new_stripes)}
    </style>
</head>

<body>

    <header>
        <img src="https://cdn.quantconnect.com/web/i/logo.png" alt="qc-logo" style="margin-top: 18px;margin-left:14px;" width="200px">
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
        </label>
        <nav>
            <ul>
                <li><a href="https://www.quantconnect.com/pricing">Pricing</a></li>
                <li><a href="https://www.quantconnect.com/datasets">Data</a></li>
                <li><a href="https://www.quantconnect.com/forum">Community</a></li>
                <li><a href="https://www.quantconnect.com/terminal">Algorithm Lab</a></li>
                <li><a href="https://www.quantconnect.com/docs">Documentation</a></li>
                <li><a href="https://www.quantconnect.com/login">Sign In</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero-header" style="background-color:#202024;color: #fff;">
        <div class="container hero-container">
            <div class="logos-container">
                <div class="logo-holder"><img src="{dataset['datasetImageLight'].lower() if dataset['datasetImageLight'] else dataset['vendorImageLight'].lower()}" alt=""></div>
                <img src="https://cdn.quantconnect.com/i/tu/ds-header-arrow.svg" alt="" width="36px" height="17px">
                <div class="logo-holder"><img src="https://cdn.quantconnect.com/i/tu/qc-icon.svg" alt=""></div>
            </div>
            <div class="hero-content">
                <h1 style="margin-bottom:.3rem;">{dataset['name']}</h1>
                <h2 style="margin-bottom: .8rem;font-weight: 300;color: #8F9CA3;">Dataset by {dataset['vendorName']}</h2>
                <p style="max-width: 50ch;margin-bottom: 1rem;">{dataset['short_description'].replace(backslash_char, '')}</p>
                <a href="https://www.quantconnect.com{dataset['url'].lower()}/pricing" class="btn btn-primary">Add Dataset</a>
            </div>
        </div>
    </div>
    <div class="stripes">
        <div style="width: 50%;"></div>
        <div style="width:15%; opacity: 0.3;"></div>
        <div style="width:35%; filter: brightness(10%);"></div>
    </div>


    <div class="container main-content">
        <div class="content">"""
    
    for part, source in SECTIONS.items():
        if part == "Pricing" and len(dataset['products']) == 0: continue
        
        html_code = CreateSection(part, dataset, source)
        html += html_code

    html += """
            <h1>Explore Other Datasets</h1>
            """
        
    for sample in samples:
        html += f"""
            <div class="dataset-listing">
                <div class="dataset-details">
                    <div class="logo-holder dataset-logo">
                        <img src="{sample['vendorImageLight']}" alt="">
                    </div>    
                    <div class="dataset-name">
                        <h2>{sample['name']}</h2>
                        <p>Dataset by {sample['vendorName']}</p>
                    </div>
                </div>
                <a href="{sample['landingFileName']}" class="btn btn-secondary">View Dataset</a>
            </div>
            """

    html += f"""
        </div>
        <div class="sidebar">
            <h3>Pricing</h3>
            <p style="margin-bottom: 1rem;">Provider offers {len(dataset['products'])} licensing options</p>
            {'<a href="#pricing">Pricing</a>' if len(dataset['products']) != 0 else ''}
            <hr style="margin: 1.2rem 0;">
            <ul style="list-style:none;">"""

    for part, source in SECTIONS.items():
        if source['h1_style'] and part != "Pricing":
            html += f'''
                <li><a href="#{source['h1_style'].split('id="')[-1].split('"')[0]}">{part}</a></li>'''
            
    html += f"""
                <li><a href="{dataset['vendorWebsite']}">Provider Website</a></li>
                <li><a href="https://www.quantconnect.com/">QuantConnect</a></li>
            </ul>
        </div>

    </div>

    <div class="seo-text">
        <div class="container">
            <h3>What is a Dataset?</h3>
            <p>Datasets are a stream of data points you use in your algorithms to make real-time trading decisions. In the QuantConnect Dataset Market, we aggregate datasets so you can easily load them into your trading algorithms and research notebooks without having to format and clean the data. The Dataset Market includes a diverse set of price, fundamental, and alternative datasets. The number of available datasets grows over time, giving you endless opportunities to discover and capture new alpha.</p>
            <h3>What is Quant Trading?</h3>
            <p>Quantitative trading is a method of trading where computer programs execute a set of defined trading rules in an automated fashion. Quants take a scientific approach to trading, applying concepts from mathematics, time series analysis, statistics, computer science, and machine learning. Compared to discretionary traders, quants can be faster to respond to new information and are less influenced by their emotions during trades. Additionally, since quants can concurrently trade many strategies while discretionary traders only have the mental capacity to trade a small number of concurrent strategies, quant traders can have more diversified portfolios.</p>
            <h3>What is Quantconnect?</h3>
            <p>QuantConnect is an open-source, community-driven algorithmic trading platform. Our trading engine is powered by <a href="https://www.quantconnect.com/lean">LEAN</a>, a cross-platform, multi-asset technology that brings cutting-edge finance to the open-source community. We believe the future of finance is automated and we plan to be the quantitative trading infrastructure of the future. We provide backtesting and a research environment for free and we provide co-located servers to run live trading algorithms for a small fee.</p>
        </div>
    </div>

    <div class="footer">
        <div class="container footer-container">

            <div class="footer-logo">
                <img src="https://cdn.quantconnect.com/web/i/splash/quantconnect_logo_byw.png" alt="footer-logo">
                <p style="font-weight:200;">QuantConnect™ 2022. All Rights Reserved</p>
            </div>

            <div class="footer-links" style="margin-top:1rem ;">
                <ul>
                    <li><h5 style="color:#8F9CA3;">TECHNOLOGY</h5></li>
                    <li><a href="https://www.quantconnect.com/terminal/">Algorithm Lab</a></li>
                    <li><a href="https://www.quantconnect.com/docs/home/home">Documentation</a></li>
                    <li><a href="https://www.quantconnect.com/forum">Community</a></li>
                    <li><a href="https://www.quantconnect.com/tutorials">Tutorials</a></li>
                    <li><a href="https://www.quantconnect.com/datasets">Data Library</a></li>
                    <li><a href="https://status.quantconnect.com/">System Status</a></li>
                </ul>
                <ul>
                    <li><h5 style="color:#8F9CA3;">COMPANY</h5></li>
                    <li><a href="https://www.quantconnect.com/about">About</a></li>
                    <li><a href="https://www.quantconnect.com/affiliates">Affiliates</a></li>
                    <li><a href="https://www.quantconnect.com/blog">Our Blog</a></li>
                    <li><a href="https://www.quantconnect.com/contact">Contact</a></li>
                    <li><a href="https://www.quantconnect.com/pricing">Pricing</a></li>
                    <li><a href="https://www.quantconnect.com/integration-partners">Integration Partners</a></li>
                    <li><a href="https://www.quantconnect.com/terms">Terms & Conditions</a></li>
                    <li><a href="https://www.quantconnect.com/privacy">Privacy Policy</a></li>
                </ul>  
            </div>
        </div>
    </div>
</body>

</html>"""

    with open(destination_folder / f'{dataset["landingFileName"]}', 'w', encoding='utf-8') as html_file:
        html_file.write(html)