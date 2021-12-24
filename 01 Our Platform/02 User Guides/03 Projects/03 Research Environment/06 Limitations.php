<p>There are several limitations to note when working with the Research Environment.</p>

<h4>Node Specifications</h4>
<p>Research nodes are not as powerful as backtesting and live trading nodes because the Research Environment only works with static data. In the Research Environment, you can control the amount of data that you're analyzing. In backtesting, there is much more data to process. The following table shows the specifications of the research node models:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/research-nodes-table.html"); ?>

<h4>Collaboration</h4>
<p>It's not currently possible to simultaneously work on the same notebook with other members in your organization because the functionality is not supported by Jupyter. Jupyter doesn't recognize an edit as a change to the file until after you run the cell.</p>

<h4>Universe Selection</h4>
<p>Universe selection is not currently supported in the Research Environment. Subscribe to <a href="https://github.com/QuantConnect/Lean/issues/1614">GitHub Issue #1614</a> to stay informed of our progress in adding this functionality.</p>

<h4>File Size</h4>
<p>Research notebooks are limited to 128KB. The reason for the file size limit on notebooks is because whenever there is a change to a file, the entire file is sent to our servers to save. The file limit ensures a fast and successful upload and download communication. Therefore, we recommend breaking up notebook files into manageable sizes.</p>