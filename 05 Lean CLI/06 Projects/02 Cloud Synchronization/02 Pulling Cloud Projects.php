<p>
    Follow these steps to pull all the cloud projects that you store in an <a href='http://www.quantconnect.com/docs/v2/cloud-platform/organizations/getting-started'>organization</a> to your local drive:
</p>

<ol>
    <li><a href="/docs/v2/lean-cli/initialization/authentication#02-Log-In">Log in</a> to the CLI if you haven't done so already.</li>
    <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> from which you want to pull projects.</li>
    <li>Run <code>lean cloud pull</code> to pull all your cloud projects to the current directory, creating directories where necessary.
<div class="cli section-example-container">
<pre>$ lean cloud pull
[1/3] Pulling 'Creative Red Mule'
Successfully pulled 'Creative Red Mule/main.py'
[2/3] Pulling 'Determined Yellow-Green Duck'
Successfully pulled 'Determined Yellow-Green Duck/main.py'
Successfully pulled 'Determined Yellow-Green Duck/research.ipynb'
[3/3] Pulling 'Halloween Strategy'
Successfully pulled 'Halloween Strategy/benchmark.py'
Successfully pulled 'Halloween Strategy/main.py'
Successfully pulled 'Halloween Strategy/research.ipynb'</pre>
</div>
    </li>
    <li>Update your projects to <a href="/docs/v2/lean-cli/projects/autocomplete#07-Imports">include the required imports</a> to run the projects locally and to make autocomplete work.</li>
</ol>

<p>
    Follow these steps to pull a single cloud project to your local drive:
</p>

<ol>
    <li><a href="/docs/v2/lean-cli/initialization/authentication#02-Log-In">Log in</a> to the CLI if you haven't done so already.</li>
    <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that stores the project.</li>
    <li>Run <code>lean cloud pull --project "&lt;projectName&gt;"</code> to pull the project named "&lt;projectName&gt;" to <span class="public-directory-name">. / &lt;projectName&gt;</span>.
<div class="cli section-example-container">
<pre>$ lean cloud pull --project "My Project"
[1/1] Pulling 'My Project'
Successfully pulled 'My Project/main.py'
Successfully pulled 'My Project/research.ipynb'</pre>
</div>
    </li>
    <li>Update your project to <a href="/docs/v2/lean-cli/projects/autocomplete#07-Imports">include the required imports</a> to run the project locally and to make autocomplete work.</li>
</ol>

<p>If you have a local copy of the project when you pull the project from the cloud, the configuration values of the cloud project overwrite the <a href='/docs/v2/lean-cli/projects/configuration#02-Properties'>configuration values of your local copy</a>.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/libraries/collaboration.html"); ?>

