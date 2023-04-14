<p>
 The Resources panel shows the <?=$cloudPlatform ? "cloud " : ""?>backtesting, research, and live trading nodes within your organization. 
 </p>
 <?=$cloudPlatform ? DOCS_VIMEO(696287022) : "" ?> 
<p>
 To view the Resources panel, <a href='<?=$projectLink?>'>open a project</a> and then, in the <?=$cloudPlatform ? "right" : "left"?> navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/resources-icon.png'> <span class='icon-name'>Resources</span> icon.
 </p>

<img class='docs-image' src='<?=$imageLink?>' alt="Node management panel">

<p>The panel displays the following information for each node:</p>
<table class='qc-table table'>
    <thead>
        <tr>
            <th>Column</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class='column-name'>Node</span></td>
            <td>The node name and model.</td>
        </tr>
        <tr>
            <td><span class='column-name'>In Use By</span></td>
            <td>The owner and name of the project using the node.</td>
        </tr>
    </tbody>
</table>

<p>To stop a running node, click the <span class='button-name'>stop</span> button next to it. You can stop nodes that you are using, but you need <a href='/docs/v2/cloud-platform/organizations/members#08-Permissions'>stop node permissions</a> to stop nodes other members are using.</p>

<p>By default, we select the best node available in your clusters when you launch a backtest or research notebook. To use a specific node, click the check box next to a node in the panel.</p>
