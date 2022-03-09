<?php
$getUpdateCodeText = function($stopExecutionLink, $deployLink) {
    echo "
        <p>Follow these steps to update the code of your live algorithm:</p>
        <ol>
            <li><a href='{$stopExecutionLink}'>Stop the algorithm</a></li>
            <li>Edit the code files</li>
            <li><a href='{$deployLink}'>Redeploy the algorithm</a></li>
        </ol>
        <p>When you stop and redeploy a live algorithm, your project's live equity curve is retained between the deployments. To erase the equity curve history, clone the project and then redeploy the cloned version of the project.</p>
    ";
}
?>





