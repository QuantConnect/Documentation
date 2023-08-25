<p>If you use <code>Adjusted</code>, <code>SplitAdjusted</code>, or <code>TotalReturn</code>, we use the entire split and dividend history to adjust historical prices. This process ensures you get the same adjusted prices, regardless of the 
<? if($writingAlgorithms) { ?>
    backtest end date. The <code>ScaledRaw</code> data normalization is only for <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history requests</a>. When you use <code>ScaledRaw</code>, we use the split and dividend history before the algorithm's current time to adjust historical prices. The <code>ScaledRaw</code> data normalization model enables you to warm up indicators with adjusted data when you subscribe to <code>Raw</code> security data.</p>
<? } else { ?> 
    <code>QuantBook</code> time. If you use <code>ScaledRaw</code>, we use the split and dividend history before the <code>QuantBook</code>'s <code>EndDate</code> to adjust historical prices.</p>
<? } ?>