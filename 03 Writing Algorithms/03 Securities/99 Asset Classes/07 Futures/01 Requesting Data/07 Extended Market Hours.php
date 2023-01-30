<?
$cCode = "AddFutureContract(_contractSymbol, extendedMarketHours: true);";
$pyCode = "self.AddFutureContract(self.contract_symbol, extendedMarketHours=True)";
$supportedIntradayData = true;
$marketHoursLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/market-hours";
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
<p>In general, we model most Futures market hours with the following segments:</p>
<table class="qc-table table">
    <thead>
        <tr>
            <th>Market Segment</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Pre-market</td>
	    <td>00:00:00 to 09:30:00</td>
        </tr>
        <tr>
            <td>Market</td>
	    <td>09:30:00 to 17:00:00</td>
        </tr>
        <tr>
            <td>Post-market</td>
	    <td>18:00:00 to 00:00:00</td>
        </tr>
    </tbody>
</table>

<p>We model it this way because some Futures, like VIX, have pre- and post-market hours, so we standardized it. With this segmentation, if you set a <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/scheduled-events'>Scheduled Events</a> for the market open, it's set for 9:30 AM instead of midnight.</p>
