<p>The optimization nodes that backtest your algorithm are not the <a href="/docs/v2/our-platform/organizations/resources#02-Backtesting-Nodes">backtesting nodes</a> in your organization. The optimization nodes are a cluster of nodes that exclusively run optimization jobs. The optimization can run with up to 24 nodes. If you use multiple nodes, the backtests run concurrently and all of the nodes are the same model. The following table shows the specifications of the optimization node models:</p> 

<table class="qc-table table">
   <thead>
      <tr>
         <th>Model</th>
         <th>Number of Cores</th>
         <th>RAM (GB)</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>O2-8</td>
         <td>2</td>
         <td>8</td>
      </tr>
      <tr>
         <td>O4-12</td>
         <td>4</td>
         <td>12</td>
      </tr>
      <tr>
         <td>O8-16</td>
         <td>8</td>
         <td>16</td>
      </tr>
   </tbody>
</table>


<p>Select the optimization node that is most appropriate for your trading algorithm. The following table explains when to use each model:</p>
<table class="table qc-table" id="optimization-node-usage-table">
    <thead>
        <tr>
            <th style="width: 33%">Model</th>
            <th style="width: 66%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>O2-8</td>
            <td>Relatively simple strategies with less than 100 assets</td>
        </tr>
        <tr>
            <td>O4-12</td>
            <td>Strategies with less than 500 assets and simple universe selections</td>
        </tr>
        <tr>
            <td>O8-16</td>
            <td>Complex strategies and machine learning</td>
        </tr>
    </tbody>
</table>

<style>
#optimization-node-usage-table th:last-child,
#optimization-node-usage-table td:last-child {
    text-align: left;
}
</style>


<p>The following table shows the <a href="/docs/v2/our-platform/organizations/resources#08-Training-Quotas">training quotas</a> of the optimization nodes:</p>

<table class="qc-table table">
   <thead>
      <tr>
         <th>Model</th>
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
th:first-child {
    width: 33%;
}

th:last-child {
    text-align: right;
    width: 33%;
}

th:nth-child(2) {
    text-align: right;
}

td:last-child {
    text-align: right;
}

td:nth-child(2) {
    text-align: right;
}

#optimization-node-usage-table th:last-child,
#optimization-node-usage-table td:last-child {
    text-align: left;
}
</style>