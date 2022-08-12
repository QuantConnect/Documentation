<p>You must create a file with data in <span class="public-file-name">CSV</span> format. Ensure the data in the file is in chronological order.</p>

<div class="section-example-container"><pre>
Date,Open,High,Low,Close,SharesTraded,Tur11er(Rs.Cr)
1997-01-01,905.2,941.4,905.2,939.55,38948210,978.21
1997-01-02,941.95,944,925.05,927.05,49118380,1150.42
1997-01-03,924.3,932.6,919.55,931.65,35263845,866.74
...
2014-07-24,7796.25,7835.65,7771.65,7830.6,117608370,6271.45
2014-07-25,7828.2,7840.95,7748.6,7790.45,153936037,7827.61
2014-07-28,7792.9,7799.9,7722.65,7748.7,116534670,6107.78
</pre></div>

<p>You can import data from local files, remote files, or REST endpoints.</p>

<h4>Local Files</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/local-files.html"); ?>

<h4>Remote Files</h4>
<p>The most common remote file providers to use are GitHub, Google Sheets, and Dropbox.</p>

<h5>GitHub</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<h5>Google Sheets</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/google-sheets.html"); ?>

<h5>Dropbox</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>