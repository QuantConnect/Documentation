<? if ($leanCli) { ?>
<p>Windows systems must meet the following requirements to install Docker:</p>
<?
    include(DOCS_RESOURCES."/cli/windows-requirements.html"); 
}
?>

<p>Follow these steps to install Docker:</p>
<ol>
    <li>Follow the <a href='https://docs.docker.com/desktop/install/windows-install/' rel='nofollow' target='_blank'>Install Docker Desktop on Windows</a> tutorial in the Docker documentation.</li>
    <p>As you install docker, enable WSL 2 features. After you install Docker and restart your computer, if Docker prompts you that the WSL 2 installation is incomplete, follow the instructions in the dialog shown by Docker to finish the WSL 2 installation.</p>
    <li>Restart your computer.</li>
    <li>Open PowerShell with adminstrator privledges and run:</li>
    <div class="cli section-example-container">
        <pre>$ wsl --update</pre>
    </div>
</ol>

<p>
    By default, Docker doesn't automatically start when your computer starts.
    So, when you run the LEAN engine with <?=$leanCli ? "the CLI" : "QuantConnect Local Platform"?> for the first time after starting your computer, you must manually start Docker.
    To automatically start Docker, open the Docker Desktop application, click <span class='menu-name'>Settings &gt; General</span>, and  then enable the <span class='box-name'>Start Docker Desktop when you log in</span> check box.
</p>
