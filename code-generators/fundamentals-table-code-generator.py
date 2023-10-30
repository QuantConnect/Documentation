destination = "Resources/datasets/data-point-attributes/fundamentals/enums.html"
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

html = """<p>The US Fundamentals dataset provides Fundamental objects. To filter Fundamental objects, you can use the MorningstarSectorCode, MorningstarIndustryGroupCode, and MorningstarIndustryCode enumeration values.</p>

<h4>Fundamentals Attributes</h4>
<p>Fundamentals objects have the following attributes:</p>
<div data-tree="QuantConnect.Data.Fundamental.Fundamental"></div>

<h4>MorningstarSectorCode Enumeration</h4>
<p>Sectors are large super categories of data. To access the sector of an Equity, use the <b>MorningstarSectorCode</b> property.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology]</pre>
</div>
<p>The <b>MorningstarSectorCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarSectorCode"></div>


<h4>MorningstarIndustryGroupCode Enumeration</h4>
<p>Industry groups are clusters of related industries which tie together.  To access the industry group of an Equity, use the <b>MorningstarIndustryGroupCode</b> property.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware]</pre>
</div>
<p>The <b>MorningstarIndustryGroupCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarIndustryGroupCode"></div>


<h4>MorningstarIndustryCode Enumeration</h4>
<p>Industries are the finest level of classification available and are the individual industries according to the Morningstar classification system.  To access the industry group of an Equity, use the <b>MorningstarIndustryCode</b> property:</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarIndustryCode.SoftwareApplication);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.AssetClassification.MorningstarIndustryCode == MorningstarIndustryCode.SoftwareApplication]</pre>
</div>
<p>The <b>MorningstarIndustryCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarIndustryCode"></div>

<h4>Exchange Id Values</h4>
<p>Exchange Id is mapped to represent the exchange that lists the Equity.  To access the exchange Id of an Equity, use the <b>PrimaryExchangeID</b> property.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.CompanyReference.PrimaryExchangeID == "NAS");</pre>
    <pre class="python">filtered = [x for x in fundamental if x.CompanyReference.PrimaryExchangeID == "NAS"]</pre>
</div>
<p>The exchanges are represented by the following string values:</p>
<table class="table qc-table table-reflow">
<thead><tr><th>String Representation</th><th>Exchange</th></tr></thead>
<tbody>
"""

for id, e in exchange_ids.items():
    html += f"""<tr><td>{id}</td><td>{e}</td></tr>
"""
html += "</tbody></table>"

with open(destination, "w", encoding="utf-8") as text:
    text.write(html)