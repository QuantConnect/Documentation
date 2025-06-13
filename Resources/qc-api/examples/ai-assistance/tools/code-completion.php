<p>The following example demonstates code completion for a specific algorithm attribute through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/ai/tools/complete', headers = get_headers(),
    json = {
        "language": "Python",
        "sentence": "self.add_equity('AAPL",
        "responseSizeLimit": 30
    })
response.json()</pre>
</div>