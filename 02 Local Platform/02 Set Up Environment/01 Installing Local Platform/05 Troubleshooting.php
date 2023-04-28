<p>The following sections explain how to solve some issues you may encounter while installing the local platform.</p>

<h4>Docker with M1/M2/Colima</h4>
<?php include(DOCS_RESOURCES."/cli/install/docker/m1.html"); ?>

<h4>Docker with WSL 2 Features</h4>
<p>When you download Docker Desktop, you need to select the <span class="box-name">Enable WSL 2 Features</span> check box. After you install Docker and restart your computer, if Docker prompts you that the WSL 2 installation is incomplete, follow the instructions in the dialog shown by Docker to finish the WSL 2 installation.</p>

<h4>Docker Not Found</h4>
<p>If you have Docker installed but the local platform can't detect it, update your <a href=''><span class='button-name'>Executable Path: Docker</span> setting</a> to be the path to your Docker executable.</p>

<h4>LEAN CLI Account Syncronization</h4>
<? include(DOCS_RESOURCES."/cli/login-sync.html"); ?>

<h4>Further Support</h4>
<p>For further support with installing the local platform, <a href='/contact'>contact us</a>.</p>
