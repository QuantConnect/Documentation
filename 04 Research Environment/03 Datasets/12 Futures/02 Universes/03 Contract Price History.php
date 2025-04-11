<p>
    The <a href='/docs/v2/writing-algorithms/universes/futures#13-Filter-Contracts'>contract filter</a> determines which Futures contracts are in your universe each trading day. 
    The default filter doesn't select any contracts.
    To change the filter, call the <code class='csharp'>SetFilter</code><code class='python'>set_filter</code> method.
</p>

<div class="section-example-container">
    <pre class="csharp">// Set the contract filter to select contracts that expire within 180 days.
future.set_filter(0, 180);</pre>
    <pre class="python"># Set the contract filter to select contracts that expire within 180 days.
future.set_filter(0, 180)</pre>
</div>

<p>To get the prices and volumes for all of the Futures contracts that pass your filter during a specific period of time, call the <code class='csharp'>FutureHistory</code><code class='python'>future_history</code> method with the <code>Symbol</code> of the continuous Futures contract, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Set the contract filter to select contracts that expire within 180 days.
var history = qb.FutureHistory(
    future.Symbol, new DateTime(2025, 4, 1), new DateTime(2025, 4, 3), Resolution.Minute, 
    fillForward: False, extendedMarketHours: False
);</pre>
    <pre class="python"># Set the contract filter to select contracts that expire within 180 days.
history = qb.future_history(
    future.Symbol, datetime(2025, 4, 1), datetime(2025, 4, 3), Resolution.MINUTE, 
    fill_forward=False, extended_market_hours=False
)</pre>
</div>

<p>The <code class='python'>future_history</code><code class='csharp'>FutureHistory</code> method returns a <code>FutureHistory</code> object. To get each <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> in the <code>FutureHistory</code> object, iterate through it.</p>

<div class="section-example-container">
    <pre class="csharp">foreach (var slice in history)
{
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        foreach (var contract in chain)
        {
            
        }
    }
}</pre>
    <pre class="python">for slice_ in history:
    for continuous_contract_symbol, chain in slice_.futures_chains.items(): 
        for contract in chain:
            pass</pre>
</div>

<div class='python'>
    <p>To convert the <code>FutureHistory</code> object to a <code>DataFrame</code> that contains the trade and quote information of each contract, use the <code>data_frame</code> property.</p>
    <div class="section-example-container">
        <pre class="python">history.data_frame</pre>
    </div>

    <div class='dataframe-wrapper'>
        <table class="dataframe python">
          <thead>
            <tr style="text-align: right;">
              <th></th>
              <th></th>
              <th></th>
              <th>askclose</th>
              <th>askhigh</th>
              <th>asklow</th>
              <th>askopen</th>
              <th>asksize</th>
              <th>bidclose</th>
              <th>bidhigh</th>
              <th>bidlow</th>
              <th>bidopen</th>
              <th>bidsize</th>
              <th>close</th>
              <th>high</th>
              <th>low</th>
              <th>open</th>
              <th>volume</th>
            </tr>
            <tr>
              <th>expiry</th>
              <th>symbol</th>
              <th>time</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th rowspan="3" valign="top">2025-06-20</th>
              <th rowspan="3" valign="top">ES YTG30NVEFCW1</th>
              <th>2025-04-01 09:31:00</th>
              <td>5631.50</td>
              <td>5639.75</td>
              <td>5631.50</td>
              <td>5639.00</td>
              <td>19.0</td>
              <td>5631.25</td>
              <td>5639.50</td>
              <td>5631.25</td>
              <td>5638.75</td>
              <td>24.0</td>
              <td>5631.25</td>
              <td>5639.75</td>
              <td>5631.25</td>
              <td>5638.75</td>
              <td>11661.0</td>
            </tr>
            <tr>
              <th>2025-04-01 09:32:00</th>
              <td>5629.00</td>
              <td>5634.00</td>
              <td>5628.00</td>
              <td>5631.50</td>
              <td>23.0</td>
              <td>5628.75</td>
              <td>5633.75</td>
              <td>5627.75</td>
              <td>5631.25</td>
              <td>1.0</td>
              <td>5629.00</td>
              <td>5633.75</td>
              <td>5627.75</td>
              <td>5631.25</td>
              <td>7613.0</td>
            </tr>
            <tr>
               <th>2025-04-01 09:33:00</th>
               <td>5636.25</td>
               <td>5638.25</td>
               <td>5628.25</td>
               <td>5629.00</td>
               <td>41.0</td>
               <td>5636.00</td>
               <td>5638.00</td>
               <td>5628.00</td>
               <td>5628.75</td>
               <td>10.0</td>
               <td>5636.00</td>
               <td>5638.00</td>
               <td>5628.25</td>
               <td>5629.00</td>
               <td>6543.0</td>
             </tr>
            <tr>
              <th>...</th>
              <th>...</th>
              <th>...</th>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
              <td>...</td>
            </tr>
            <tr>
              <th rowspan="3" valign="top">2025-09-19</th>
              <th rowspan="3" valign="top">ES YVXOP65RE0HT</th>
              <th>2025-04-02 16:58:00</th>
              <td>5631.25</td>
              <td>5638.50</td>
              <td>5630.00</td>
              <td>5637.25</td>
              <td>1.0</td>
              <td>5629.00</td>
              <td>5636.00</td>
              <td>5627.25</td>
              <td>5635.00</td>
              <td>2.0</td>
              <td>5634.00</td>
              <td>5635.00</td>
              <td>5634.00</td>
              <td>5635.00</td>
              <td>3.0</td>
            </tr>
            <tr>
              <th>2025-04-02 16:59:00</th>
              <td>5625.75</td>
              <td>5636.00</td>
              <td>5624.00</td>
              <td>5631.25</td>
              <td>1.0</td>
              <td>5623.50</td>
              <td>5632.75</td>
              <td>5620.75</td>
              <td>5629.00</td>
              <td>2.0</td>
              <td>5627.00</td>
              <td>5627.00</td>
              <td>5627.00</td>
              <td>5627.00</td>
              <td>1.0</td>
            </tr>
            <tr>
              <th>2025-04-02 17:00:00</th>
              <td>5633.25</td>
              <td>5634.50</td>
              <td>5625.75</td>
              <td>5625.75</td>
              <td>1.0</td>
              <td>5630.50</td>
              <td>5632.00</td>
              <td>5623.50</td>
              <td>5623.50</td>
              <td>1.0</td>
              <td>5628.75</td>
              <td>5628.75</td>
              <td>5626.75</td>
              <td>5626.75</td>
              <td>36.0</td>
            </tr>
          </tbody>
        </table>
    </div>
</div>

<p>To get the expiration dates of all the contracts in a <code>FutureHistory</code> object, call the <code class='python'>get_expiry_dates</code><code class='csharp'>GetExpiryDates</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">var expiries = history.GetExpiryDates();</pre>
    <pre class="python">expiries = history.get_expiry_dates()</pre>
</div>
