<p>
    You can use the <code>--data-provider-historical</code> option to change where the data is retrieved.
    This option updates the <a href="/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration">Lean configuration file</a>, so you don't need to use this option multiple times for the same data provider if you are not switching between them. 
    The following table shows the available data providers and their required options in non-interactive mode:
</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th style="width: 25%"><code>--data-provider-historical</code></th>
            <th>Required Options</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Alpaca</code></td>
            <td>N/A</td>
        </tr>
        
        <tr>
            <td rowspan="2"><code>AlphaVantage</code></td>
            <td><code>--alpha-vantage-api-key</code></td>
        </tr>
        <tr><td><code>--alpha-vantage-price-plan</code></td></tr>

        <tr>
            <td rowspan="3"><code>Binance</code></td>
            <td><code>--binance-exchange-name</code></td>
        </tr>
        <tr><td><code>--binance-api-key</code> or <code>--binanceus-api-key</code></td></tr>
        <tr><td><code>--binance-api-secret</code> or <code>--binanceus-api-secret</code></td></tr>

        <tr>
            <td rowspan="2"><code>Bybit</code></td>
            <td><code>--bybit-api-key</code></td>
        </tr>
        <tr><td><code>--bybit-api-secret</code></td></tr>

        <tr>
            <td rowspan="2"><code>CoinApi</code></td>
            <td><code>--coinapi-api-key</code></td>
        </tr>
        <tr><td><code>--coinapi-product</code></td></tr>

        <tr>
            <td rowspan="2"><code>"Coinbase Advanced Trade"</code></td>
            <td><code>--coinbase-api-name</code></td>
        </tr>
        <tr><td><code>--coinbase-api-private-key</code></td></tr>
        
        <tr>
            <td><code>FactSet</code></td>
            <td><code>--factset-auth-config-file</code></td>
        </tr>
        
        <tr>
            <td rowspan="2"><code>IEX</code></td>
            <td><code>--iex-cloud-api-key</code></td>
        </tr>
        <tr><td><code>--iex-price-plan</code></td></tr>

        <tr>
            <td rowspan="3"><code>"Interactive Brokers"</code></td>
            <td><code>--ib-user-name</code></td>
        </tr>
        <tr><td><code>--ib-account</code></td></tr>
        <tr><td><code>--ib-password</code></td></tr>

        <tr>
            <td rowspan="5"><code>IQFeed</code></td>
            <td><code>--iqfeed-iqconnect</code></td>
        </tr>
        <tr><td><code>--iqfeed-username</code></td></tr>
        <tr><td><code>--iqfeed-password</code></td></tr>
        <tr><td><code>--iqfeed-version</code></td></tr>
        <tr><td><code>--iqfeed-host</code></td></tr>

        <tr>
            <td rowspan="3"><code>Kraken</code></td>
            <td><code>--kraken-api-key</code></td>
        </tr>
        <tr><td><code>--kraken-api-secret</code></td></tr>
        <tr><td><code>--kraken-verification-tier</code></td></tr>

        <tr>
            <td><code>Local</code></td>
            <td>N/A</td>
        </tr>

        <tr>
            <td rowspan="3"><code>Oanda</code></td>
            <td><code>--oanda-account-id</code></td>
        </tr>
        <tr><td><code>--oanda-access-token</code></td></tr>
        <tr><td><code>--oanda-environment</code></td></tr>

        <tr>
            <td><code>Polygon</code></td>
            <td><code>--polygon-api-key</code></td>
        </tr>

        <tr>
            <td><code>QuantConnect</code></td>
            <td>N/A</td>
        </tr>

        <tr>
            <td rowspan="7"><code>"Terminal Link"</code></td>
            <td><code>--terminal-link-connection-type</code></td>
        </tr>
        <tr><td><code>--terminal-link-environment</code></td></tr>
        <tr><td><code>--terminal-link-server-host</code></td></tr>
        <tr><td><code>--terminal-link-server-port</code></td></tr>
        <tr><td><code>--terminal-link-emsx-broker</code></td></tr>
        <tr><td><code>--terminal-link-openfigi-api-key</code></td></tr>
        <tr><td><code>--terminal-link-server-auth-id</code> if you use <code>--terminal-link-connection-type SAPI</code></td></tr>

        <tr>
            <td><code>ThetaData</code></td>
            <td><code>--thetadata-subscription-plan</code></td>
        </tr>

        <tr>
            <td><code>TradeStation</code></td>
            <td>N/A</td>
        </tr>
    </tbody>
</table>

<? if (!$isResearch) { ?>
<p>You can use the <code>--download-data</code> flag as an alias for <code>--data-provider-historical QuantConnect</code>. This data provider automatically downloads the required data files when your backtest requests them. After it downloads a data file, it stores it in your local <span class='public-directory-name'>data</span> directory so that in future runs, it won't have to download it again. If the file contain data for multiple days (for example, daily Equity price data files), the <code>ApiDataProvider</code> re-downloads the file if your local version is at least 7 days old. To adjust this setting, update the <code>downloader-data-update-period</code> value in your <a href="/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration">Lean configuration</a> file.</p>
<? } ?>
