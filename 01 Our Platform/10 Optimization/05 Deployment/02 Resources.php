<p>The optimization nodes that backtest your algorithm are not the <a href="/docs/v2/our-platform/organizations/resources#02-Backtesting-Nodes">backtesting nodes</a> in your organization. The optimization nodes are a cluster of nodes that exclusively run optimization jobs. The optimization can concurrently run multiple backtests if you use multiple nodes, but the maximum number of nodes you can use depends on the node type. The following table describes the node types:</p> 

<table class="qc-table table" id='optimization-node-description-table'>
   <thead>
      <tr>
         <th>Type</th>
         <th>Description</th>
         <th>Number of Cores</th>
         <th>RAM (GB)</th>
         <th>Max Cluster Size</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>O2-8</td>
         <td>Relatively simple strategies with less than 100 assets</td>
         <td>2</td>
         <td>8</td>
         <td>10</td>
      </tr>
      <tr>
         <td>O4-12</td>
         <td>Strategies with less than 500 assets and simple universe selections</td>
         <td>4</td>
         <td>12</td>
         <td>6</td>
      </tr>
      <tr>
         <td>O8-16</td>
         <td>Complex strategies and machine learning</td>
         <td>8</td>
         <td>16</td>
         <td>4</td>
      </tr>
   </tbody>
</table>

<p>The following table shows the <a href="/docs/v2/our-platform/organizations/resources#08-Training-Quotas">training quotas</a> of the optimization node types:</p>

<table class="qc-table table" class='optimization-node-quota-table'>
   <thead>
      <tr>
         <th style='wrap:nowrap'>Type</th>
         <th>Capacity (min)</th>
         <th>Refill Rate (min/day)</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>O2-8</td>
         <td>30</td>
         <td>5</td>
      </tr>
      <tr>
         <td>O4-12</td>
         <td>60</td>
         <td>10</td>
      </tr>
      <tr>
         <td>O8-16</td>
         <td>90</td>
         <td>15</td>
      </tr>
   </tbody>
</table>

<style>
#optimization-node-description-table th:nth-child(3),
#optimization-node-description-table td:nth-child(3),
#optimization-node-description-table th:nth-child(4),
#optimization-node-description-table td:nth-child(4),
#optimization-node-description-table th:nth-child(5),
#optimization-node-description-table td:nth-child(5) {
    text-align: right;
}
#optimization-node-description-table td:nth-child(1) {
    white-space: nowrap;
}

#optimization-node-quota-table td:nth-child(2),
#optimization-node-quota-table td:nth-child(3) {
    text-align: right;
}
</style>