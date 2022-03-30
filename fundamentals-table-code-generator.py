from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/AssetClassificationHelper.cs").read().decode("utf-8").split('\n')
exchange_ids = {
    'NYS': "New York Stock Exchange (NYSE)",
    'NAS': "NASDAQ", 
    'ASE': "American Stock Exchange (AMEX)",
    'TSE': "Tokyo Stock Exchange", 
    'AMS': "Amsterdam Internet Exchange", 
    'SGO': "Santiago Stock Exchange", 
    'XMAD': "Madrid Stock Exchange", 
    'ASX': "Australian Securities Exchange", 
    'BVMF': "B3 (stock exchange)", 
    'LON': "London Stock Exchange", 
    'TKS': "Istanbul Stock Exchange Settlement and Custody Bank", 
    'SHG': "Shanghai Exchange", 
    'LIM': "Lima Stock Exchange", 
    'FRA': "Frankfurt Stock Exchange", 
    'JSE': "Johannesburg Stock Exchange", 
    'MIL': "Milan Stock Exchange", 
    'TAE': "Tel Aviv Stock Exchange",
    'STO': "Stockholm Stock Exchange", 
    'ETR': "Deutsche Boerse Xetra Core", 
    'PAR': "Paris Stock Exchange", 
    'BUE': "Buenos Aires Stock Exchange", 
    'KRX': "Korea Exchange", 
    'SWX': "SIX Swiss Exchange", 
    'PINX': "Pink Sheets (OTC)", 
    'CSE': "Canadian Securities Exchange", 
    'PHS': "Philippine Stock Exchange", 
    'MEX': "Mexican Stock Exchange", 
    'TAI': "Taiwan Stock Exchange", 
    'IDX': "Indonesia Stock Exchange", 
    'OSL': "Oslo Stock Exchange", 
    'BOG': "Colombia Stock Exchange", 
    'NSE': "National Stock Exchange of India", 
    'HEL': "Nasdaq Helsinki", 
    'MISX': "Moscow Exchange", 
    'HKG': "Hong Kong Stock Exchange", 
    'IST': "Istanbul Stock Exchange", 
    'BOM': "Bombay Stock Exchange", 
    'TSX': "Toronto Stock Exchange",
    'BRU': "Brussels Stock Exchange", 
    'BATS': "BATS Global Markets", 
    'ARCX': "NYSE Arca", 
    'GREY': "Grey Market (OTC)", 
    'DUS': "Dusseldorf Stock Exchange", 
    'BER': "Berlin Stock Exchange", 
    'ROCO': "Taipei Exchange", 
    'CNQ': "Canadian Trading and Quotation System Inc.", 
    'BSP': "Bangko Sentral ng Pilipinas", 
    'NEOE': "NEO Exchange"
}

html = ""

active = False

for x in raw:
    if "class MorningstarSectorCode" in x:
        html += """<h4>MorningstarSectorCode Enumeration</h4>
<p>Sectors are large super categories of data. They are accessed with the <code>MorningstarSectorCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarSectorCode.Technology);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology]</pre>
</div>
<table class="table qc-table table-reflow" id="enum-code-table">
<thead>
<tr><th style="width: 80%;"><code>MorningstarSectorCode</code></th><th style="width: 20%;">Sector Code</th></tr>
</thead>
<tbody>"""
        active = True
        
    elif "class MorningstarIndustryGroupCode" in x:
        html += """<h4>MorningstarIndustryGroupCode Enumeration</h4>
<p>Industry groups are clusters of related industries which tie together. They are accessed with the <code>MorningstarIndustryGroupCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware]</pre>
</div>
<table class="table qc-table table-reflow" id="enum-code-table">
<thead>
<tr><th style="width: 80%;"><code>MorningstarIndustryGroupCode</code></th><th style="width: 20%;">Industry Group Code</th></tr>
</thead>
<tbody>"""
        active = True
        
    elif "class MorningstarIndustryCode" in x:
        html += """<h4>MorningstarIndustryCode Enumeration</h4>
<p>Industries are the finest level of classification available, and are the individual industries according to the Morningstar classification system. They are accessed with the <code>MorningstarIndustryCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryCode.SoftwareApplication);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.SoftwareInfrastructure]</pre>
</div>
<table class="table qc-table table-reflow" id="enum-code-table">
<thead>
<tr><th style="width: 80%;"><code>MorningstarIndustryCode</code></th><th style="width: 20%;">Industry Code</th></tr>
</thead>
<tbody>"""
        active = True
        
    elif "}" in x and active:
        html += """</tbody>
</table>

"""
        active = False
        
    if active and " = " in x:
        equal_sign_split = x.split(" = ")
        
        space_split = equal_sign_split[0].split(" ")

        enum = space_split[-1]
        code = equal_sign_split[-1][:-1]
        
        html += f'''<tr><td><code>{enum}</code></td><td align="right">{code}</td></tr>
'''

html += """<h4>Morningstar ExchangeID Enumeration</h4>
<p>Exchange ID is mapped to represent the exchange of the equity listed in. They are accessed with the <code>PrimaryExchangeID</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.CompanyReference.PrimaryExchangeID == "NAS");</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.CompanyReference.PrimaryExchangeID == "NAS"]</pre>
</div>
<table class="table qc-table table-reflow" id="enum-code-table">
<thead>
<tr><th style="width: 10%;"><code>MorningstarExchangeID</code></th><th style="width: 90%;">Exchange represented</th></tr>
</thead>
<tbody>
"""

for id, e in exchange_ids.items():
    html += f"""<tr><td>{id}</td><td>{e}</td></tr>
"""
html += "</tbody></table>"

with open("02 Writing Algorithms/14 Datasets/04 Morningstar/01 US Fundamental Data/05 Data Point Attributes.html", "w", encoding="utf-8") as text:
    text.write("""<style>
#enum-code-table td:nth-child(2), 
#enum-code-table th:nth-child(2) {
    text-align: right;
}
</style>

<h4>FineFundamental Attributes</h4>
<div data-tree="QuantConnect.Data.Fundamental.FineFundamental"></div>

""")
    text.write(html)