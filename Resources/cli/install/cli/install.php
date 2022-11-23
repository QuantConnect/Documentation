<?php
$getInstallText = function($pipLink, $dockerLink)
{
    echo "
<p>Before you install the LEAN CLI, check if it's already installed. To check if it's already installed, open a terminal and then run <code>lean --version</code>. If the LEAN CLI is already installed, <a href='/docs/v2/lean-cli/installation/installing-lean-cli#03-Stay-Up-To-Date'>upgrade it</a> instead of installing it again.</p>
    
<p>Follow these steps to install the LEAN CLI:</p>

<ol>
    <li><a href='{$pipLink}'>Install pip</a>.</li>
    <li><a href='{$dockerLink}'>Install Docker</a>.</li>
    <li>If you are on a Windows machine, open PowerShell as the adminstrator for the following commands.</li>
    <li>Install the LEAN CLI with pip.
    <div class='cli section-example-container'>
        <pre>$ pip install lean</pre>
     </div>
     This commands downloads all the CLI's dependencies and makes the <code>lean</code> command available in the terminal.
     </li>
    <li>If you use Linux, you may need to install <code>tkinter</code>
    <div class='cli section-example-container'>
        <pre>$ sudo apt-get install python3-tk</pre>
    </div>
    </li>
    <li>Verify the installation was successful.
    <div class='cli section-example-container'>
        <pre>$ lean --version</pre>
    </div>
    </li>
</ol>
";
}
?>
