<p>The following sections explain how to solve some issues you may encounter while installing Local Platform.</p>

<? if ($os == "mac") { ?>
<h4>M1 or M2 Chips</h4>
<? include(DOCS_RESOURCES."/cli/install/docker/m1.html"); ?>
<? } ?>

<? if ($os == "windows") { ?>
<h4>Docker with WSL 2 Features</h4>
<? include(DOCS_RESOURCES."/cli/install/docker/wsl2.html"); ?>
<? } ?>

<h4>Docker Not Found</h4>
<p>If you have Docker installed but the Local Platform can't detect it, update your <a href=''><span class='button-name'>Executable Path: Docker</span> setting</a> to be the path to your Docker executable.</p>

<h4>LEAN CLI Account Syncronization</h4>
<? include(DOCS_RESOURCES."/cli/login-sync.html"); ?>

<h4>Further Support</h4>
<p>For further support with installing Local Platform, <a href='/contact'>contact us</a>.</p>
