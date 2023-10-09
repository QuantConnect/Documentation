<?php include(DOCS_RESOURCES."/getting-started/research-environment/open-notebooks.html"); ?>

<p>When you open a notebook, it automatically tries to connect to the correct Jupyter server and select the correct kernel, which can take up to one minute. If the top-right corner of the notebook displays a <span class='button-name'>base (Python x.x.x)</span> button, wait for the button to change to <span class='csharp button-name'>Foundation-C#-Default</span><span class='python button-name'>Foundation-Py-Default</span> before you run the cells. If you run cells before the notebook connects to the server and kernel, you may get the following error message:</p>
<div class='error-messages'>NameError: name 'QuantBook' is not defined</div>
