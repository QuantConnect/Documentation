<p>Follow these steps to create a new version control repository for one of your <a href='<?=$workspaceLink?>'>organization workspaces</a>:</p>

<ol>
    <li>In your version control system, <a href='https://docs.github.com/en/repositories/creating-and-managing-repositories/creating-a-new-repository' rel='nofollow' target='_blank'>create a new repository</a> for the project.</li>
    
    <li>Open a terminal in your organization workspace and then <a href='https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository' rel='nofollow' target='_blank'>clone</a> the new repository to a temporary directory.</li>
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
