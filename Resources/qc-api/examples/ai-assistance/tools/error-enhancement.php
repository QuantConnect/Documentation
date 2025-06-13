<p>The following example demonstates how to get additional context and suggestions for error messages through the cloud API.</p>

<div class="python section-example-container testable">
    <pre><? include(DOCS_RESOURCES."/qc-api/get_headers.py"); ?>

response = post(f'{BASE_URL}/ai/tools/error-enhance/', headers = get_headers(), 
    json = {
        "language": "Python",
        "error":
        {
            "message": '''  at initialize
        self._option = self.add_index_option(\"SPX\", Resolution.MINUTE, \"SPXW\").symbol
                    ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    in c1afe80a-e056-4841-b0c1-d9c562cf2bd8.py: line 15
    The specified market wasn't found in the markets lookup. Requested: spxw. You can add markets by calling QuantConnect.Market.Add(string,int) (Parameter 'market')''',
            "stacktrace": ""
        }
    })
response.json()</pre>
</div>