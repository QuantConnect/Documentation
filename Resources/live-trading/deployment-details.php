<?
// $brokerageName: the brokerage that shares the details.
// $deploymentDetails: key => description of each detail the brokerage shares.
?>
<p>When you deploy a live algorithm with <?=$brokerageName?>, the brokerage shares some details of the deployment, like the account it trades. Use them to tell concurrent deployments apart or to check that the algorithm connected to the account you expect. The details never include credentials.</p>

<p>The following table describes the deployment details that <?=$brokerageName?> shares:</p>

<table class="qc-table table" id="deployment-details-table">
   <thead>
      <tr>
        <th>Key</th>
        <th>Description</th>
      </tr>
   </thead>
   <tbody>
<? foreach ($deploymentDetails as $key => $description) { ?>
      <tr>
        <td><code><?=$key?></code></td>
        <td><?=$description?></td>
      </tr>
<? } ?>
   </tbody>
</table>

<? include(DOCS_RESOURCES."/live-trading/read-deployment-details.php"); ?>
