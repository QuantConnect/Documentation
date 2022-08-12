<p>You must create a file with data in <span class="public-file-name">JSON</span> format. Ensure the data in the file is in chronological order.</p>

<div class="section-example-container"><pre>[
    {
      "Date": "20170704",
      "Symbols": ["SPY", "QQQ", "FB", "AAPL", "IWM"]
    },
    {
      "Date": "20170706",
      "Symbols": ["QQQ", "AAPL", "IWM", "FB", "GOOGL"]
    },
    ...
    {
      "Date": "20170801",
      "Symbols": ["QQQ", "FB", "AAPL", "IWM", "GOOGL"]
    },
    {
      "Date": "20170802",
      "Symbols": ["QQQ", "IWM", "FB", "BAC", "GOOGL"]
    }
]</pre></div>

<p>You can import data from local files, remote files, or REST endpoints.</p>

<h4>Local Files</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/local-files.html"); ?>

<h4>Remote Files</h4>
<p>The most common remote file providers to use are GitHub and Dropbox.</p>

<h5>GitHub</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<h5>Dropbox</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>