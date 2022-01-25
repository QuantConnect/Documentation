
<h4>Orders</h4>
<p>The number of orders that you can place in a single backtest depends on the tier of your organization. The maximum number of orders per backtest for each tier is the following:<br></p>

<table class="qc-table table" id='order-quotas-table'>
   <thead>
      <tr>
         <th style="width: 50%;">Tier</th>
         <th style="width: 50%;">Orders Quota</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>Free</td>
         <td>10K</td>
      </tr>
      <tr>
         <td>Quant Researcher</td>
         <td>10M</td>
      </tr>
      <tr>
         <td>Team</td>
         <td>Unlimited</td>
      </tr>
      <tr>
         <td>Trading Firm</td>
         <td>Unlimited</td>
      </tr>
      <tr>
         <td>Institution</td>
         <td>Unlimited</td>
      </tr>
   </tbody>
</table>

<style>
#order-quotas-table td:last-child, 
#order-quotas-table th:last-child {
    text-align: right;
}
</style>


<h4>Logs</h4>
<p>The amount of logs that you can generate per backtest and per day depends on the tier of your organization. The following table describes the amount of logs that you can generate on each tier:<br></p>

<?php echo file_get_contents(DOCS_RESOURCES."/log-limits.html"); ?>
