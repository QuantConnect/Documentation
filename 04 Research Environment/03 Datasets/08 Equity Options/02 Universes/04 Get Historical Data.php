<p>You need a <a href='/docs/v2/research-environment/datasets/equity-options/universes#03-Create-Subscriptions'>subscription</a> before you can request historical data for Equity Option contracts. To get historical data for all of the Equity Option contracts that pass your filter during a specific period of time, call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the underlying Equity <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>option_history = qb.option_history(
    equity_symbol, qb.start_date-timedelta(days=2), qb.start_date, Resolution.MINUTE, 
    fill_forward=False, extended_market_hours=False
)</pre>
    <pre class='csharp'>var optionHistory = qb.OptionHistory(
    equitySymbol, qb.StartDate-TimeSpan.FromDays(2), qb.StartDate, Resolution.Minute, 
    fillForward: False, extendedMarketHours: False
);</pre>
</div>
