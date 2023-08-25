<p>Follow these steps to push the changes of your organization workspace to your version control system:</p>
<ol>
<? if($leanCli) { ?>
    <li><a href='/docs/v2/lean-cli/projects/cloud-synchronization#02-Pulling-Cloud-Projects'>Pull all your cloud projects</a>, creating directories where necessary.</li>
    <div class="cli section-example-container">
        <pre>$ lean cloud pull</pre>
    </div>
<? } ?>
    <li><? if ($leanCLI) { ?>A<? } else { ?>Open a terminal in your organization workspace and then a<? } ?>dd the project directories and the <span class='public-directory-name'>Library</span>.</li>
    <div class="cli section-example-container">
        <pre>$ git add Library/
$ git add &lt;projectDirectory1&gt;/
$ git add &lt;projectDirectory2&gt;/</pre>
    </div>

    <li>Commit the changes.</li>
    <div class="cli section-example-container">
        <pre>$ git commit -am "Latest Updates"</pre>
    </div>

    <li>Push the changes to the repository.</li>
    <div class="cli section-example-container">
        <pre>$ git push</pre>
    </div>
<ol>
