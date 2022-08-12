<p>You must create a file with data in <span class="public-file-name">JSON</span> format. Ensure the data in the file is in chronological order.</p>

<div class="section-example-container"><pre>
[
    {
      "Date": "1997-01-01",
      "Open": 905.2,
      "High": 941.4,
      "Low": 905.2,
      "Close": 939.55,
      "SharesTraded": 38948210,
      "Tur11er(Rs.Cr)": 978.21
    },
    {
      "Date": "1997-01-02",
      "Open": 941.95,
      "High": 944,
      "Low": 925.05,
      "Close": 927.05,
      "SharesTraded": 49118380,
      "Tur11er(Rs.Cr)": 1150.42
    },
    ...
    {
      "Date": "2014-07-25",
      "Open": 7828.2,
      "High": 7840.95,
      "Low": 7748.6,
      "Close": 7790.45,
      "SharesTraded": 153936037,
      "Tur11er(Rs.Cr)": 7827.61
    },
    {
      "Date": "2014-07-28",
      "Open": 7792.9,
      "High": 7799.9,
      "Low": 7722.65,
      "Close": 7748.7,
      "SharesTraded": 116534670,
      "Tur11er(Rs.Cr)": 6107.78
    }
]
</pre></div>

<p>You can import data from local files, remote files, or REST endpoints.</p>

<h4>Local Files</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/local-files.html"); ?>

<h4>Remote Files</h4>
<p>The most common remote file providers to use are GitHub and Dropbox. Google Sheets don't support exporting data in Json format.</p>

<h5>GitHub</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<h5>Dropbox</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>