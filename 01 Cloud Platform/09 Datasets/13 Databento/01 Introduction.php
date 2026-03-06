<? include(DOCS_RESOURCES."/brokerages/introduction-by-brokerage/databento.html"); ?>

<p>
  The Databento data provider serves asset prices from Databento. 
  Instead of using the data from QuantConnect or your brokerage, you can use data from <a rel='nofollow' target='_blank' href='https://databento.com/'>Databento</a> if you're deploying a live algorithm and have an API key. 
  To get an API key, see the <a href='https://databento.com/portal/keys' rel='nofollow' target='_blank'>API Keys</a> page on the Databento website.
  This page explains our integration with Databento and its functionality.
</p>

<p>To view the implementation of the Databento integration, see the <a href='https://github.com/QuantConnect/Lean.DataSource.Databento' rel='nofollow' target='_blank'>Lean.DataSource.Databento repository</a>.</p>

<p>
  QuantConnect Cloud currently only supported streaming Databento data during live trading. 
  To download Databento for backtesting, research, and optimizations, use the <a href='/docs/v2/lean-cli/datasets/databento'>CLI</a>.
</p>