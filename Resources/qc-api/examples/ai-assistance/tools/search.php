<p>The following example demonstates searching content in QuantConnect through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/ai/tools/search', headers = get_headers(),
    json = {
       "language": "Python",
        "criteria": [
        {
            "input": "option",
            "type": "Docs",
            "count": 1
        }
    ]
    })
response.json()</pre>
</div>