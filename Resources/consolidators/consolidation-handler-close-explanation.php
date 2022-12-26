<?php
$getConsolidationExplanationText = function($consolidationTextResolution, $consolidationTextReceiveTime1, $consolidationTextReceiveTime2)
{
	return "If you subscribe to {$consolidationTextResolution} resolution data for Bitcoin and create an hourly consolidator, you receive consolidated bars at the top of each hour. However, if you subscribe to {$consolidationTextResolution} resolution data for the regular trading hours of US Equities and create a daily consolidator, you receive consolidated bars {$consolidationTextReceiveTime1} AM Eastern Time (ET). The consolidated bar for US Equities doesn't close at 4:00 PM ET because the day isn't over. The consolidated bar for US Equities also doesn't close at midnight because your algorithm doesn't receive minute resolution data after 4:00 PM ET until {$consolidationTextReceiveTime2} AM ET.";
}
?>
