from urllib.request import urlopen

raw = urlopen("https://raw.githubusercontent.com/QuantConnect/Lean/master/Common/Data/Fundamental/AssetClassificationHelper.cs").read().decode("utf-8").split('\n')

html = ""

one = ""
two = ""
active = False

for x in raw:
    if "class MorningstarSectorCode" in x:
        html += """<h4>MorningstarSectorCode Enumeration</h4>
<p>Sectors are large super categories of data. They are accessed with the <code>MorningstarSectorCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarSectorCode.Technology);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarSectorCode == MorningstarSectorCode.Technology]</pre>
</div>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 80%;">Sector Helper Class</th><th style="width: 20%;">Sector Code</th></tr>
</thead>
<tbody>"""
        one = "MorningstarSectorCode"
        active = True
        
    elif "class MorningstarIndustryGroupCode" in x:
        html += """<h4>MorningstarIndustryGroupCode Enumeration</h4>
<p>Industry groups are clusters of related industries which tie together. They are accessed with the <code>MorningstarIndustryGroupCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryGroupCode.ApplicationSoftware]</pre>
</div>
<table class="table qc-table table-reflow">
<thead>
<tr><th style="width: 80%;">Industry Group Helper Class</th><th style="width: 20%;">Industry Group Code</th></tr>
</thead>
<tbody>"""
        one = "MorningstarIndustryGroupCode"
        active = True
        
    elif "class MorningstarIndustryCode" in x:
        html += """<h4>MorningstarIndustryCode Enumeration</h4>
<p>Industries are the finest level of classification available, and are the individual industries according to the Morningstar classification system. They are accessed with the <code>MorningstarIndustryCode</code> property:</p>
<div class="section-example-container">
    <pre class="csharp">filteredFine = fine.Where(x => x.AssetClassification.MorningstarIndustryGroupCode == MorningstarIndustryCode.SoftwareApplication);</pre>
    <pre class="python">filtered_fine = [x for x in fine if x.AssetClassification.MorningstarIndustryCode == MorningstarSectorCode.SoftwareInfrastructure]</pre>
</div>
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
        
        html += f'<tr><td><code>{one}.{two}</code></td><td align="right">{code}</td></tr>'

with open("/02 Writing Algorithms/02 User Guides/04 Alternative Datasets/21 Morningstar/01 US Fundamental Data/05 Data Point Attributes.html", "w", encoding="utf-8") as text:
    text.write("""<h4>FineFundamental Attributes</h4>
<div data-tree="QuantConnect.Data.Fundamental.FineFundamental"></div>

""")
    text.write(html)