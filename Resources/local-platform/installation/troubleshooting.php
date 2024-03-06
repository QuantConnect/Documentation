<p>The following sections explain how to solve some issues you may encounter while installing Local Platform.</p>

<? if ($os == "windows") { ?>
<h4>Docker with WSL 2 Features</h4>
<? include(DOCS_RESOURCES."/cli/install/docker/wsl2.html"); ?>

<h4>Windows Security</h4>
<p>If you cannot synchonize your workpace, configure controlled folder access on your computer.</p>
<ol>
    <li>Press the Windows key to open the Start Menu.</li>
    <li>Type Windows Security in the search bar and click the Open option in the right pane.</li>
    <li>On the Windows Security homepage, click the Virus & threat protection option in the left sidebar.</li>
    <li>Click Manage settings under the 'Virus & threat protection settings' section.</li>
    <li>Scroll down to the 'Ransomware protection' section and click Manage ransomware protection.</li>
    <li>Click on Allow an app through Controlled folder access under "Controlled folder access"</li>
    <li>Click on Add an allowed app, select Recently blocked apps, and allow lean.exe</li>
</ol>
<? } ?>

<h4>Docker Not Found</h4>
<p>If you have Docker installed but the Local Platform can't detect it, update your <a href=''><span class='button-name'>Executable Path: Docker</span> setting</a> to be the path to your Docker executable.</p>

<h4>LEAN CLI Account Syncronization</h4>
<? include(DOCS_RESOURCES."/cli/login-sync.html"); ?>

<h4>Further Support</h4>
<p>For further support with installing Local Platform, <a href='/contact'>contact us</a>.</p>
