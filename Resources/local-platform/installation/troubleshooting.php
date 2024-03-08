<p>The following sections explain how to solve some issues you may encounter while installing Local Platform.</p>

<? if ($os == "windows") { ?>
<h4>Docker with WSL 2 Features</h4>
<? include(DOCS_RESOURCES."/cli/install/docker/wsl2.html"); ?>

<h4>Windows Security</h4>
<p>If you can't synchonize your workpace, follow these steps to configure controlled folder access on your computer:</p>
<ol>
    <li>Press the <span class='key-combinations'>Windows</span> key to open the Start Menu.</li>
    <li>In the search bar, enter "Ransomware protection" and then press <span class='key-combinations'>Enter</span>.</li>
    <li>On the Ransomware protection page, enable controlled folder access.</li>
    <li>Click <span class='button-name'>Allow an app through Controlled folder access</span>.</li>
    <li>Click <span class='button-name'>Add an allowed app</span> and then click <span class='button-name'>Recently blocked apps</span> from the drop-down menu.</li>
    <li>Allow lean.exe.</li>
</ol>
<? } ?>

<h4>Docker Not Found</h4>
<p>If you have Docker installed but the Local Platform can't detect it, update your <a href=''><span class='button-name'>Executable Path: Docker</span> setting</a> to be the path to your Docker executable.</p>

<h4>LEAN CLI Account Syncronization</h4>
<? include(DOCS_RESOURCES."/cli/login-sync.html"); ?>

<h4>Further Support</h4>
<p>For further support with installing Local Platform, <a href='/contact'>contact us</a>.</p>
