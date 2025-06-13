<p>The following example demonstates getting a list of Lean versions through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/lean/versions/read', headers = get_headers())
response.json()</pre>
</div>