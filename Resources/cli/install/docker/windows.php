<p>Windows systems must meet the following requirements to install Docker:</p>

<? 
if ($leanCli) {
    include(DOCS_RESOURCES."/cli/windows-requirements.html"); 
}
?>

<p>To install Docker, see <a href='https://docs.docker.com/desktop/install/windows-install/' rel='nofollow' target='_blank'>Install Docker Desktop on Windows</a> in the Docker documentation. As you install docker, enable WSL 2 features. After you install Docker and restart your computer, if Docker prompts you that the WSL 2 installation is incomplete, follow the instructions in the dialog shown by Docker to finish the WSL 2 installation.</p>

<p>
    By default, Docker doesn't automatically start when your computer starts.
    So, when you run the LEAN engine with <?=$leanCli ? "the CLI" : "QuantConnect Local Platform"?> for the first time after starting your computer, you must manually start Docker.
    To automatically start Docker, open the Docker Desktop application, click <span class='menu-name'>Settings &gt; General</span>, and  then enable the <span class='box-name'>Start Docker Desktop when you log in</span> check box.
</p>
