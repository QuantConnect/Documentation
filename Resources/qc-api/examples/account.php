<p>The following example demonstates reading the organization account status through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/account/read', headers = get_headers())
response.json()</pre>
</div>