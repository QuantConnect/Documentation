<p>Follow these steps to set your version control system:</p>

<ol>
    <li>In your version control system, <a href='https://docs.github.com/en/repositories/creating-and-managing-repositories/creating-a-new-repository' rel='nofollow' target='_blank'>create a new repository</a> for the project.</li>
    
    <li>Open a terminal in your <a href='/docs/v2/local-platform/development-environment/organization-workspaces'>organization workspace</a> and then <a href='https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository' rel='nofollow' target='_blank'>clone</a> the new repository to a temporary directory.</li>
    <div class="cli section-example-container">
        <pre>$ git clone https://github.com/&lt;userName&gt;/&lt;repoName&gt;.git temp</pre>
    </div>

    <li>Move the <span class='public-directory-name'>.git</span> directory from the temporary directory to the workspace directory.</li>
    <div class="cli section-example-container">
        <pre>$ mv temp/.git &lt;workspaceDirectory&gt;/.git</pre>
    </div>

    <li>Delete the temporary directory.</li>
    <div class="cli section-example-container">
        <pre>$ rm -r temp</pre>
    </div>
</ol>

<p>To upload the latest version, follow these steps.</p>
<ol>
<? if($leanCli) { ?>
    <li>Run <code>lean cloud pull</code> to <a href='/docs/v2/lean-cli/projects/cloud-synchronization#02-Pulling-Cloud-Projects'>pull all your cloud projects</a> to the current directory, creating directories where necessary.
<div class="cli section-example-container">
        <pre>$ lean cloud pull</pre>
    </div>
<? } ?>
    <li>Add the project directories and <span class='public-directory-name'>Library</span>.</li>
    <div class="cli section-example-container">
        <pre>$ git add Library/
$ git add &lt;projectDirectory1&gt;/
$ git add &lt;projectDirectory2&gt;/</pre>
    </div>

    <li>Commit the changes and push to the repository.</li>
    <div class="cli section-example-container">
        <pre>$ git commit -am "Latest Updates"</pre>
    </div>

    <li>Push the changes to the repository.</li>
    <div class="cli section-example-container">
        <pre>$ git push</pre>
    </div>
<ol>