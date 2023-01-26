<p>
    If you are running Docker on Windows using the legacy Hyper-V backend instead of the new WSL 2 backend, you need to enable file sharing for your temporary directories and for your workspace.
    To do so, open your Docker settings, go to <span class='menu-name'>Resources &gt; File Sharing</span> and add <span class='public-directory-name'>C: &nbsp; / &nbsp; Users &nbsp; / &nbsp; &lt;username&gt; &nbsp; / &nbsp; AppData &nbsp; / &nbsp; Local &nbsp; / &nbsp; Temp</span> and your workspace path to the list.
    Click <span class='button-name'>Apply &amp; Restart</span> after making the required changes.
</p>
