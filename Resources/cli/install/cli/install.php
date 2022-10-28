<?php
$getInstallText = function($pipLink, $dockerLink)
{
    echo "
<p>Follow these steps to install the LEAN CLI:</p>

<ol>
    <li><a href='{$pipLink}'>Install pip</a>.</li>
    <li><a href='{$dockerLink}'>Install Docker</a>.</li>
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