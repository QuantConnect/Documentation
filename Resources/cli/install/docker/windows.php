<p>
    To install Docker on Windows, your computer must meet the following requirements:
</p>

<ul>
    <li>A 64-bit processor</li>
    <li>4GB RAM or more</li>
    <li>Windows 10, version 1903 or higher (released May 2019)</li>
    <li>Hardware virtualization enabled in the BIOS</li>
</ul>

<p>
    If you meet the requirements, follow these steps to install Docker on your computer:
</p>

<ol>
    <li>Download <a href="https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe" target="_blank">Docker Desktop Installer.exe</a>.</li>
    <li>Run the downloaded installer and ensure the <span class="box-name">Enable WSL 2 Features</span> check box is selected when prompted for it.</li>
    <li>Follow the instructions in the installation wizard to complete the installation.</li>
    <li>Restart your computer to ensure changes are propagated.</li>
</ol>

<p>
    After you install Docker and restart your computer, if Docker prompts you that the WSL 2 installation is incomplete, follow the instructions in the dialog shown by Docker to finish the WSL 2 installation.
</p>

<p>
    By default, Docker doesn't automatically start when your computer starts.
    So, when you run the LEAN engine with <?=$isCLIDocs ? "the CLI" : "QuantConnect Local"?> for the first time after starting your computer, you must manually start Docker.
    To automatically start Docker, open the Docker Desktop application, click <span class='menu-name'>Settings &gt; General</span>, and  then enable the <span class='box-name'>Start Docker Desktop when you log in</span> check box.
</p>