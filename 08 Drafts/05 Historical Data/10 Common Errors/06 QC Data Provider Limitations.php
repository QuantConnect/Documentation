<?
  include(DOCS_RESOURCES."/data-feeds/historical-data/intro.php");
  include(DOCS_RESOURCES."/data-feeds/historical-data/base-limits.html");
?>

<p>
  In live trading, history requests for Index or Options with the QuantConnect data provider only returns data up to the end of the previous day. 
  For example, if you make a history request for these asset classes at 1 PM Eastern Time (ET), you won't get any data for the current day.
</p>

<p>For more information about the QuantConnect data provider, see <a href='/docs/v2/cloud-platform/datasets/quantconnect'>QuantConnect</a>.</p>
