<p>You must create a file with data in <span class="public-file-name">CSV</span> format. Ensure the data in the file is in chronological order.</p>

<div class="section-example-container"><pre>
20170704,SPY,QQQ,FB,AAPL,IWM
20170706,QQQ,AAPL,IWM,FB,GOOGL
20170707,IWM,AAPL,FB,BAC,GOOGL
...
20170729,SPY,QQQ,FB,AAPL,IWM
20170801,QQQ,FB,AAPL,IWM,GOOGL
20170802,QQQ,IWM,FB,BAC,GOOGL
</pre></div>

<p>You can import data from local files, remote files, or REST endpoints.</p>

<h4>Remote Files</h4>
<p>The most common remote file providers to use are GitHub, Google Sheets, and Dropbox.</p>

<h5>GitHub</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/github.html"); ?>

<h5>Google Sheets</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/google-sheets.html"); ?>

<h5>Dropbox</h5>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/dropbox.html"); ?>