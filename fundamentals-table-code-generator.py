from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/AssetClassificationHelper.cs").read().decode("utf-8").split('\n')

html = ""

one = ""
two = ""
active = False

for x in raw:
    if "class MorningstarSectorCode" in x:
        html += """<h4>MorningstarSectorCode Enumeration</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 80%;">Sector Helper Class</th><th style="width: 20%;">Sector Code</th></tr>
</thead>
<tbody>"""
        one = "MorningstarSectorCode"
        active = True
        
    elif "class MorningstarIndustryGroupCode" in x:
        html += """<h4>MorningstarIndustryGroupCode Enumeration</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 80%;">Industry Group Helper Class</th><th style="width: 20%;">Industry Group Code</th></tr>
</thead>
<tbody>"""
        one = "MorningstarIndustryGroupCode"
        active = True
        
    elif "class MorningstarIndustryCode" in x:
        html += """<h4>MorningstarIndustryCode Enumeration</h4>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 80%;">Industry Helper Class</th><th style="width: 20%;">Industry Code</th></tr>
</thead>
<tbody>"""
        one = "MorningstarIndustryCode"
        active = True
        
    elif "}" in x and active:
        html += """</tbody>
</table>

"""
        active = False
        
    if active and " = " in x:
        equal_sign_split = x.split(" = ")
        
        space_split = equal_sign_split[0].split(" ")

        two = space_split[-1]
        code = equal_sign_split[-1][:-1]
        
        html += f'<tr><td>{one}.{two}</td><td>{code}</td></tr>'

with open("02 Writing Algorithms/02 User Guides/04 Alternative Datasets/21 Morningstar/01 US Fundamental Data/05 Data Point Attributes.html", "w", encoding="utf-8") as text:
    text.write("""<h4>FineFundamental Attributes</h4>
<div data-tree="QuantConnect.Data.Fundamental.FineFundamental"></div>

""")
    text.write(html)