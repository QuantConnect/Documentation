<p>The <code>InteractiveBrokersBrokerageModel</code> supports several order types, order properties, and order updates.</p>

<?php include(DOCS_RESOURCES."/brokerages/interactive-brokers/orders.php"); ?>


<h4>Order Size Limits</h4>

<p>The following table shows the maximum number of units you can buy of each currency when the currency is the <a href='/docs/v2/writing-algorithms/securities/key-concepts#03-Quote-Currency'>base currency</a> in a Forex pair:</p>

<table class="table qc-table" id="order-size-limits-table">
    <thead>
        <tr>
            <th>Base Currency</th>
            <th>Description</th>
            <th>Maximum Order Size (Millions)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>USD</td>
            <td>US Dollar</td>
            <td>7</td>
        </tr>
        <tr>
            <td>AUD</td>
            <td>Australian Dollar</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CAD</td>
            <td>Canadian Dollar</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CHF</td>
            <td>Swiss Franc</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CNH</td>
            <td>China Renminbi (offshore)</td>
            <td>40</td>
        </tr>
        <tr>
            <td>CZK</td>
            <td>Czech Koruna</td>
            <td>0</td>
        </tr>
        <tr>
            <td>DKK</td>
            <td>Danish Krone</td>
            <td>35</td>
        </tr>
        <tr>
            <td>EUR</td>
            <td>Euro</td>
            <td>5</td>
        </tr>
        <tr>
            <td>GBP</td>
            <td>British Pound Sterling</td>
            <td>4</td>
        </tr>
        <tr>
            <td>HKD</td>
            <td>Hong Kong Dollar</td>
            <td>50</td>
        </tr>
        <tr>
            <td>HUF</td>
            <td>Hungarian Forint</td>
            <td>0</td>
        </tr>
        <tr>
            <td>HKD</td>
            <td>Israeli Shekel</td>
            <td>0</td>
        </tr>
        <tr>
            <td>KRW</td>
            <td>Korean Won</td>
            <td>750</td>
        </tr>
        <tr>
            <td>JPY</td>
            <td>Japanese Yen</td>
            <td>550</td>
        </tr>
        <tr>
            <td>MXN</td>
            <td>Mexican Peso</td>
            <td>70</td>
        </tr>
        <tr>
            <td>NOK</td>
            <td>Norwegian Krone</td>
            <td>35</td>
        </tr>
        <tr>
            <td>NZD</td>
            <td>New Zealand Dollar</td>
            <td>8</td>
        </tr>
        <tr>
            <td>RUB</td>
            <td>Russian Ruble</td>
            <td>30</td>
        </tr>
        <tr>
            <td>SEK</td>
            <td>Swedish Krona</td>
            <td>40</td>
        </tr>
        <tr>
            <td>SGD</td>
            <td>Singapore Dollar</td>
            <td>8</td>
        </tr>
    </tbody>
</table>

<style>
#order-size-limits-table td:last-child, 
#order-size-limits-table th:last-child {
    text-align: right;
}
</style>

