destination = "Resources/datasets/data-point-attributes/fundamentals/enums.html"
exchange_ids = {
    'AMEX': "NYSE American",
    'ARCA': "NYSE Arca",
    'ASX': "Australian Securities Exchange",
    'BATS': "Cboe BZX Exchange",
    'BUE': "Buenos Aires Stock Exchange",
    'BVMF': "B3",
    'DUB': "Euronext Dublin",
    'DUS': "Dusseldorf Stock Exchange",
    'FRA': "Frankfurt Stock Exchange",
    'HKSE': "Hong Kong Stock Exchange",
    'ISX': "Indonesia Stock Exchange",
    'JSE': "Johannesburg Stock Exchange",
    'LIM': "Lima Stock Exchange",
    'LSE': "London Stock Exchange",
    'MEX': "Mexican Stock Exchange",
    'MIL': "Borsa Italiana",
    'MISX': "Moscow Exchange",
    'MUN': "Munich Stock Exchange",
    'NAS': "Nasdaq",
    'NEOE': "Cboe Canada",
    'NYSE': "New York Stock Exchange",
    'OCSE': "Nasdaq Copenhagen",
    'OHEL': "Nasdaq Helsinki",
    'OSL': "Oslo Stock Exchange",
    'OSTO': "Nasdaq Stockholm",
    'OTCM': "OTC Markets",
    'STU': "Stuttgart Stock Exchange",
    'TPE': "Taiwan Stock Exchange",
    'TSE': "Tokyo Stock Exchange",
    'TSX': "Toronto Stock Exchange",
    'TSXV': "TSX Venture Exchange",
    'XAMS': "Euronext Amsterdam",
    'XBRU': "Euronext Brussels",
    'XCNQ': "Canadian Securities Exchange",
    'XKRX': "Korea Exchange",
    'XMAD': "Madrid Stock Exchange",
    'XPAR': "Euronext Paris",
    'XSGO': "Santiago Stock Exchange",
    'XSWX': "SIX Swiss Exchange",
    'XTAE': "Tel Aviv Stock Exchange",
    'XTER': "Deutsche Boerse Xetra"
}

html = """<p>The US Fundamentals dataset provides Fundamental objects. To filter Fundamental objects, you can use the <code>MorningstarSectorCode</code>, <code>MorningstarIndustryGroupCode</code>, and <code>MorningstarIndustryCode</code> enumeration values.</p>

<h4>Fundamentals Attributes</h4>
<p>Fundamentals objects have the following attributes:</p>
<div data-tree="QuantConnect.Data.Fundamental.Fundamental"></div>

<h4>MorningstarSectorCode Enumeration</h4>
<p>Sectors are large super categories of data. To access the sector of an Equity, use the <b class="csharp">MorningstarSectorCode</b><b class="python">morningstar_sector_code</b> property.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.asset_classification.morningstar_sector_code == MorningstarSectorCode.TECHNOLOGY]</pre>
</div>
<p>The <b>MorningstarSectorCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarSectorCode"></div>


<h4>MorningstarIndustryGroupCode Enumeration</h4>
<p>Industry groups are clusters of related industries which tie together.  To access the industry group of an Equity, use the <b class="csharp">MorningstarIndustryGroupCode</b><b class="python">morningstar_industry_group_code</b> property.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.asset_classification.morningstar_industry_group_code == MorningstarIndustryGroupCode.APPLICATION_SOFTWARE]</pre>
</div>
<p>The <b>MorningstarIndustryGroupCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarIndustryGroupCode"></div>


<h4>MorningstarIndustryCode Enumeration</h4>
<p>Industries are the finest level of classification available and are the individual industries according to the Morningstar classification system.  To access the industry group of an Equity, use the <b class="csharp">MorningstarIndustryCode</b><b class="python">morningstar_industry_code</b> property:</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.AssetClassification.MorningstarIndustryCode == MorningstarIndustryCode.SoftwareApplication);</pre>
    <pre class="python">filtered = [x for x in fundamental if x.asset_classification.morningstar_industry_code == MorningstarIndustryCode.SOFTWARE_APPLICATION]</pre>
</div>
<p>The <b>MorningstarIndustryCode</b> enumeration has the following members:</p>
<div data-tree="QuantConnect.Data.Fundamental.MorningstarIndustryCode"></div>

<h4>Exchange Id Values</h4>
<p>Exchange Ids identify the exchange that lists the Equity. The <b class="csharp">SecurityReference.ExchangeId</b><b class="python">security_reference.exchange_id</b> property is the exchange of the share class. The <b class="csharp">CompanyReference.PrimaryExchangeID</b><b class="python">company_reference.primary_exchange_id</b> property is the exchange of the primary share of the company, which can be outside the US.</p>
<div class="section-example-container">
    <pre class="csharp">filtered = fundamental.Where(x =&gt; x.SecurityReference.ExchangeId == "NYSE");</pre>
    <pre class="python">filtered = [x for x in fundamental if x.security_reference.exchange_id == "NYSE"]</pre>
</div>
<p>The exchange Id is part of the fundamental data, so it's <code class="csharp">null</code><code class="python">None</code> when <code class="csharp">HasFundamentalData</code><code class="python">has_fundamental_data</code> is <code class="csharp">false</code><code class="python">False</code>. The share class exchange Id is one of <code>NAS</code>, <code>NYSE</code>, <code>AMEX</code>, <code>OTCM</code>, <code>ARCA</code>, or <code>BATS</code>. The primary exchange Id can be any of the following string values:</p>
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