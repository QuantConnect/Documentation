<p>
  Overfitting occurs when you fine-tune the parameters of an algorithm to fit
  the detail and noise of backtesting data to the extent that it negatively
  impacts the performance of the algorithm on new data. The problem is that the
  parameters don't necessarily apply to new data and thus negatively impact the
  algorithm's ability to generalize and trade well in all market conditions. The following
  table shows ways that overfitting can manifest itself:
</p>

<table class="table qc-table">
  <thead>
    <tr>
      <th style='width: 25%'>Data Practice</th>
      <th style='width: 75%'>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <a target='_blank' rel='nofollow' href="https://en.wikipedia.org/wiki/Data_dredging">Data Dredging</a
        >
      </td>
      <td>
        Performing many statistical tests on data and only paying attention to
        those that come back with significant results.
      </td>
    </tr>
    <tr>
      <td>
        <a target='_blank' rel='nofollow' href="https://en.wikipedia.org/wiki/Hyperparameter_optimization">Hyper-Tuning Parameters</a
        >
      </td>
      <td>
        Manually changing algorithm parameters to produce better results without
        altering the test data.
      </td>
    </tr>
    <tr>
      <td>
        <a target='_blank' rel='nofollow' href="https://en.wikipedia.org/wiki/Overfitting#Regression">Overfit Regression Models</a
        >
      </td>
      <td>
        Regression, machine learning, or other statistical models with too many
        variables will likely introduce overfitting to an algorithm.
      </td>
    </tr>
    <tr>
      <td>Stale Testing Data</td>
      <td>
        Not changing the backtesting data set when testing the algorithm. Any
        improvements might not be able to be generalized to different datasets.
      </td>
    </tr>
  </tbody>
</table>
<p>
  An algorithm that is dynamic and generalizes to new data is more valuable to
  funds and individual investors. It is more likely to survive across different
  market conditions and apply to new asset classes and markets.
</p>
