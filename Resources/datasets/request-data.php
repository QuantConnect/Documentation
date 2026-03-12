<? 
$pyVar = $writingAlgorithms ? "self" : "qb";
$pyFutureVar = $writingAlgorithms ? "self." : "";
$cVar = $writingAlgorithms ? "" : "qb.";
$envName = $writingAlgorithms ? "algorithm" : "notebook";
$cPrintMethod = $writingAlgorithms ? "Log" : "Console.WriteLine";
$pyPrintMethod = $writingAlgorithms ? "self.Log" : "print";
?>

<p>
    The simplest form of history request is for a known set of <code>Symbol</code> objects.
    <? if ($writingAlgorithms) {?> This is common for fixed universes of securities or when you need to prepare new securities added to your algorithm.<? } ?>
    History requests return slightly different data depending on the overload you call. The data that returns is in ascending order from oldest to newest.
    <? if ($writingAlgorithms) {?> This order is necessary to use the data to warm up indicators. <? } ?>
</p>
    
<h4>Single Symbol History Requests</h4>

<p>To request history for a single asset, pass the asset <code>Symbol</code> to the <code class="csharp">History</code><code class="python">history</code> method. The return type of the method call depends on the history request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>. The following table describes the return type of each request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Request Type</th>
            <th>Return Data Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>No argument</td>
	    <td><code class='python'>DataFrame</code><code class='csharp'>List&lt;TradeBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>TradeBar</code></td>
	    <td><code class='python'>List[TradeBars]</code><code class='csharp'>List&lt;TradeBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>QuoteBar</code></td>
	    <td><code class='python'>List[QuoteBars]</code><code class='csharp'>List&lt;QuoteBar&gt;</code></td>
        </tr>
        <tr>
	    <td><code>Tick</code></td>
	    <td><code class='python'>List[Ticks]</code><code class='csharp'>List&lt;Tick&gt;</code></td>
        </tr>
	<tr>
	    <td><code class='placeholder-text'>alternativeDataClass</code><br>(ex: <code>CBOE</code>)</td>
	    <td><span class='python'><code>List[<span class='placeholder-text'>alternativeDataClass</span>]</code><br>(ex: <code>List[CBOE]</code>)</span><span class='csharp'><code>List&lt;<span class='placeholder-text'>alternativeDataClass</span>&gt;</code><br>(ex: <code>List&lt;CBOE&gt;</code>)</span></td>
        </tr>
    </tbody>
</table>
<p class='python'>Each row of the DataFrame represents the prices at a point in time. Each column of the DataFrame is a property of that price data (for example, open, high, low, and close (OHLC)). If you request a DataFrame object and pass <code>TradeBar</code> as the first argument, the DataFrame that returns only contains the OHLC and volume columns. If you request a DataFrame object and pass <code>QuoteBar</code> as the first argument, the DataFrame that returns contains the OHLC of the bid and ask and it contains OHLC columns, which are the respective means of the bid and ask OHLC values. If you request a DataFrame and don't pass <code>TradeBar</code> or <code>QuoteBar</code> as the first arugment, the DataFrame that returns contains columns for all of the data that's available for the given resolution.</p>

<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 1: Requesting By Bar Count: 5 bars at the security resolution:</b>
vix_symbol = <?=$pyVar?>.add_data(CBOE, "VIX", Resolution.DAILY).symbol
cboe_data = <?=$pyVar?>.history[CBOE](vix_symbol, 5)

btc_symbol = <?=$pyVar?>.add_crypto("BTCUSD", Resolution.MINUTE).symbol
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, 5)
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, 5)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, 5)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, 5)
df = <?=$pyVar?>.history(btc_symbol, 5)   # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-07-09 04:01:00</th><td>21596.65</td><td>21607.03</td><td>21591.50</td><td>21602.53</td><td>0.229349</td><td>21593.66</td><td>21604.13</td><td>21590.42</td><td>21600.24</td><td>0.180000</td><td>21596.65</td><td>21605.56</td><td>21591.51</td><td>21599.96</td><td>2.299479</td></tr>
    <tr><th></th><th>2022-07-09 04:02:00</th><td>21611.12</td><td>21611.13</td><td>21596.58</td><td>21596.65</td><td>0.460583</td><td>21611.11</td><td>21611.12</td><td>21593.66</td><td>21593.66</td><td>0.000217</td><td>21611.12</td><td>21611.12</td><td>21596.28</td><td>21596.58</td><td>4.046283</td></tr>
    <tr><th></th><th>2022-07-09 04:03:00</th><td>21619.75</td><td>21624.05</td><td>21606.98</td><td>21611.12</td><td>0.001000</td><td>21619.74</td><td>21621.81</td><td>21605.30</td><td>21611.11</td><td>0.000368</td><td>21619.75</td><td>21621.81</td><td>21605.61</td><td>21611.12</td><td>6.201696</td></tr>
    <tr><th></th><th>2022-07-09 04:04:00</th><td>21612.18</td><td>21623.30</td><td>21602.54</td><td>21619.75</td><td>0.021005</td><td>21610.67</td><td>21620.54</td><td>21602.36</td><td>21619.74</td><td>0.050000</td><td>21610.47</td><td>21620.54</td><td>21602.87</td><td>21619.74</td><td>8.323808</td></tr>
    <tr><th></th><th>2022-07-09 04:05:00</th><td>21608.68</td><td>21613.22</td><td>21602.01</td><td>21612.18</td><td>0.002000</td><td>21608.67</td><td>21612.04</td><td>21598.37</td><td>21610.67</td><td>0.050000</td><td>21606.92</td><td>21613.05</td><td>21598.43</td><td>21613.05</td><td>2.890605</td></tr>
  </tbody>
</table>
</div>
<pre class='csharp'><b>// EXAMPLE 1: Requesting By Bar Count: 5 bars at the security resolution:</b>
var vixSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX", Resolution.Daily).Symbol;
var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, 5);

var btcSymbol = <?=$cVar?>AddCrypto("BTCUSD", Resolution.Minute).Symbol;
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, 5);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, 5);
var tradeBars2 = <?=$cVar?>History(btcSymbol, 5);</pre>

	
<pre class='python'><b># EXAMPLE 2: Requesting By Bar Count: 5 bars with a specific resolution:</b>
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, 5, Resolution.DAILY)
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, 5, Resolution.MINUTE)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, 5, Resolution.MINUTE)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, 5, Resolution.MINUTE)
df = <?=$pyVar?>.history(btc_symbol, 5, Resolution.MINUTE)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-07-09 04:01:00</th><td>21596.65</td><td>21607.03</td><td>21591.50</td><td>21602.53</td><td>0.229349</td><td>21593.66</td><td>21604.13</td><td>21590.42</td><td>21600.24</td><td>0.180000</td><td>21596.65</td><td>21605.56</td><td>21591.51</td><td>21599.96</td><td>2.299479</td></tr>
    <tr><th></th><th>2022-07-09 04:02:00</th><td>21611.12</td><td>21611.13</td><td>21596.58</td><td>21596.65</td><td>0.460583</td><td>21611.11</td><td>21611.12</td><td>21593.66</td><td>21593.66</td><td>0.000217</td><td>21611.12</td><td>21611.12</td><td>21596.28</td><td>21596.58</td><td>4.046283</td></tr>
    <tr><th></th><th>2022-07-09 04:03:00</th><td>21619.75</td><td>21624.05</td><td>21606.98</td><td>21611.12</td><td>0.001000</td><td>21619.74</td><td>21621.81</td><td>21605.30</td><td>21611.11</td><td>0.000368</td><td>21619.75</td><td>21621.81</td><td>21605.61</td><td>21611.12</td><td>6.201696</td></tr>
    <tr><th></th><th>2022-07-09 04:04:00</th><td>21612.18</td><td>21623.30</td><td>21602.54</td><td>21619.75</td><td>0.021005</td><td>21610.67</td><td>21620.54</td><td>21602.36</td><td>21619.74</td><td>0.050000</td><td>21610.47</td><td>21620.54</td><td>21602.87</td><td>21619.74</td><td>8.323808</td></tr>
    <tr><th></th><th>2022-07-09 04:05:00</th><td>21608.68</td><td>21613.22</td><td>21602.01</td><td>21612.18</td><td>0.002000</td><td>21608.67</td><td>21612.04</td><td>21598.37</td><td>21610.67</td><td>0.050000</td><td>21606.92</td><td>21613.05</td><td>21598.43</td><td>21613.05</td><td>2.890605</td></tr>
  </tbody>
</table>
</div>
<pre class='csharp'><b>// EXAMPLE 2: Requesting By Bar Count: 5 bars with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, 5, Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, 5, Resolution.Minute);
var tradeBars2 = <?=$cVar?>History(btcSymbol, 5, Resolution.Minute);</pre>


<pre class='python'><b># EXAMPLE 3: Requesting By a Trailing Period: 3 days of data at the security resolution:</b> 
eth_symbol = <?=$pyVar?>.add_crypto('ETHUSD', Resolution.TICK).symbol
ticks = <?=$pyVar?>.history[Tick](eth_symbol, timedelta(days=3))
ticks_df = <?=$pyVar?>.history(eth_symbol, timedelta(days=3))

vix_data = <?=$pyVar?>.history[CBOE](vix_symbol, timedelta(days=3)) 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, timedelta(days=3)) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, timedelta(days=3))
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, timedelta(days=3))
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, timedelta(days=3))
df = <?=$pyVar?>.history(btc_symbol, timedelta(days=3))  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-07-08 04:01:00</th><td>22141.16</td><td>22153.87</td><td>22116.38</td><td>22123.94</td><td>0.400000</td><td>22137.01</td><td>22153.86</td><td>22113.42</td><td>22123.93</td><td>0.136000</td><td>22144.89</td><td>22153.87</td><td>22116.38</td><td>22125.99</td><td>30.040683</td></tr>
    <tr><th></th><th>2022-07-08 04:02:00</th><td>22144.58</td><td>22165.07</td><td>22135.77</td><td>22141.16</td><td>0.057960</td><td>22144.57</td><td>22145.74</td><td>22132.86</td><td>22137.01</td><td>0.907468</td><td>22144.58</td><td>22164.97</td><td>22135.76</td><td>22140.51</td><td>30.609148</td></tr>
    <tr><th></th><th>2022-07-08 04:03:00</th><td>22121.26</td><td>22146.46</td><td>22118.96</td><td>22144.58</td><td>0.013358</td><td>22121.09</td><td>22146.45</td><td>22118.92</td><td>22144.57</td><td>0.001288</td><td>22121.78</td><td>22146.45</td><td>22118.96</td><td>22144.57</td><td>31.833847</td></tr>
    <tr><th></th><th>2022-07-08 04:04:00</th><td>22114.21</td><td>22121.26</td><td>22097.83</td><td>22121.26</td><td>0.002549</td><td>22114.20</td><td>22121.16</td><td>22096.63</td><td>22121.09</td><td>0.030212</td><td>22114.21</td><td>22121.18</td><td>22097.52</td><td>22121.09</td><td>10.173673</td></tr>
    <tr><th></th><th>2022-07-08 04:05:00</th><td>22108.03</td><td>22115.72</td><td>22097.83</td><td>22114.21</td><td>1.276832</td><td>22108.02</td><td>22114.31</td><td>22097.82</td><td>22114.20</td><td>0.000200</td><td>22108.02</td><td>22114.98</td><td>22097.82</td><td>22114.98</td><td>7.675039</td></tr>
  </tbody>
</table>
</div>

<pre class='csharp'><b>// EXAMPLE 3: Requesting By a Trailing Period: 3 days of data at the security resolution:</b>
var ethSymbol = <?=$cVar?>AddCrypto("ETHUSD", Resolution.Tick).Symbol;
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, TimeSpan.FromDays(3));

var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, TimeSpan.FromDays(3));
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, TimeSpan.FromDays(3));
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, TimeSpan.FromDays(3));
var tradeBars2 = <?=$cVar?>History(btcSymbol, TimeSpan.FromDays(3));</pre>
	
	
	
<pre class='python'><b># EXAMPLE 4: Requesting By a Trailing Period: 3 days of data with a specific resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, timedelta(days=3), Resolution.DAILY) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, timedelta(days=3), Resolution.MINUTE)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, timedelta(days=3), Resolution.TICK)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, timedelta(days=3), Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, timedelta(days=3), Resolution.MINUTE)
ticks_df = <?=$pyVar?>.history(eth_symbol, timedelta(days=3), Resolution.TICK)
df = <?=$pyVar?>.history(btc_symbol, timedelta(days=3), Resolution.HOUR)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-07-08 05:00:00</th><td>21974.81</td><td>22238.28</td><td>21954.82</td><td>22123.94</td><td>0.024746</td><td>21972.03</td><td>22233.55</td><td>21951.59</td><td>22123.93</td><td>0.000829</td><td>21974.74</td><td>22235.11</td><td>21951.60</td><td>22125.99</td><td>739.867210</td></tr>
    <tr><th></th><th>2022-07-08 06:00:00</th><td>21836.71</td><td>22058.00</td><td>21744.01</td><td>21974.81</td><td>0.001000</td><td>21836.50</td><td>22057.99</td><td>21744.00</td><td>21972.03</td><td>0.042814</td><td>21836.45</td><td>22058.00</td><td>21744.00</td><td>21972.10</td><td>661.371392</td></tr>
    <tr><th></th><th>2022-07-08 07:00:00</th><td>21821.14</td><td>21868.66</td><td>21754.91</td><td>21836.71</td><td>0.009425</td><td>21819.25</td><td>21866.78</td><td>21752.74</td><td>21836.50</td><td>0.008751</td><td>21821.17</td><td>21868.41</td><td>21752.78</td><td>21836.63</td><td>501.845020</td></tr>
    <tr><th></th><th>2022-07-08 08:00:00</th><td>21787.12</td><td>21849.87</td><td>21713.45</td><td>21821.14</td><td>0.001000</td><td>21786.73</td><td>21848.39</td><td>21709.80</td><td>21819.25</td><td>0.001000</td><td>21787.95</td><td>21849.87</td><td>21709.80</td><td>21821.47</td><td>566.432573</td></tr>
    <tr><th></th><th>2022-07-08 09:00:00</th><td>21425.18</td><td>21812.07</td><td>21415.14</td><td>21787.12</td><td>0.135118</td><td>21423.23</td><td>21810.77</td><td>21415.13</td><td>21786.73</td><td>0.000078</td><td>21425.90</td><td>21810.77</td><td>21415.14</td><td>21786.73</td><td>583.159373</td></tr>
  </tbody>
</table>
</div>
<pre class="python"># Important Note: Period history requests are relative to "now" <?=$envName?> time.</pre>


<pre class='csharp'><b>// EXAMPLE 4: Requesting By a Trailing Period: 3 days of data with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, TimeSpan.FromDays(3), Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, TimeSpan.FromDays(3), Resolution.Minute);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, TimeSpan.FromDays(3), Resolution.Tick);
var tradeBars2 = <?=$cVar?>History(btcSymbol, TimeSpan.FromDays(3), Resolution.Minute);</pre>


<pre class='python'><b># EXAMPLE 5: Requesting By a Defined Period: 3 days of data at the security resolution:</b> 
start_time = datetime(2022, 1, 1)
end_time = datetime(2022, 1, 4)

vix_data = <?=$pyVar?>.history[CBOE](vix_symbol, start_time, end_time) 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, start_time, end_time) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, start_time, end_time)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, start_time, end_time)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time)
ticks_df = <?=$pyVar?>.history(Tick, eth_symbol, start_time, end_time)
df = <?=$pyVar?>.history(btc_symbol, start_time, end_time)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-01-01 05:01:00</th><td>46724.00</td><td>46724.00</td><td>46682.98</td><td>46698.99</td><td>0.490830</td><td>46723.99</td><td>46723.99</td><td>46678.61</td><td>46696.14</td><td>0.002460</td><td>46724.00</td><td>46724.00</td><td>46678.61</td><td>46696.45</td><td>12.898891</td></tr>
    <tr><th></th><th>2022-01-01 05:02:00</th><td>46719.51</td><td>46742.76</td><td>46709.30</td><td>46724.00</td><td>0.000977</td><td>46719.50</td><td>46736.09</td><td>46708.37</td><td>46723.99</td><td>0.001225</td><td>46719.51</td><td>46742.76</td><td>46710.29</td><td>46723.99</td><td>19.193879</td></tr>
    <tr><th></th><th>2022-01-01 05:03:00</th><td>46713.32</td><td>46722.14</td><td>46697.04</td><td>46719.51</td><td>0.223169</td><td>46709.32</td><td>46719.50</td><td>46697.00</td><td>46719.50</td><td>0.043362</td><td>46713.31</td><td>46719.50</td><td>46697.04</td><td>46719.50</td><td>3.677070</td></tr>
    <tr><th></th><th>2022-01-01 05:04:00</th><td>46694.78</td><td>46715.84</td><td>46690.21</td><td>46713.32</td><td>0.065120</td><td>46694.77</td><td>46715.30</td><td>46688.76</td><td>46709.32</td><td>0.021412</td><td>46694.78</td><td>46715.84</td><td>46690.20</td><td>46709.33</td><td>3.759909</td></tr>
    <tr><th></th><th>2022-01-01 05:05:00</th><td>46693.46</td><td>46706.83</td><td>46690.03</td><td>46694.78</td><td>0.070000</td><td>46693.45</td><td>46701.72</td><td>46690.00</td><td>46694.77</td><td>0.008974</td><td>46693.37</td><td>46703.07</td><td>46690.00</td><td>46694.78</td><td>5.703675</td></tr>
  </tbody>
</table>
</div>

<pre class='csharp'><b>// EXAMPLE 5: Requesting By a Defined Period: 3 specific days of data at the security resolution:</b>
var startTime = new DateTime(2022, 1, 1);
var endTime = new DateTime(2022, 1, 4);

var cboeData = <?=$cVar?>History&lt;CBOE&gt;(vixSymbol, startTime, endTime);
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, startTime, endTime);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, startTime, endTime);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, startTime, endTime);
var tradeBars2 = <?=$cVar?>History(btcSymbol, startTime, endTime);</pre>

	
<pre class='python'><b># EXAMPLE 6: Requesting By a Defined Period: 3 days of data with a specific resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar](btc_symbol, start_time, end_time, Resolution.DAILY) 
quote_bars = <?=$pyVar?>.history[QuoteBar](btc_symbol, start_time, end_time, Resolution.MINUTE)
ticks = <?=$pyVar?>.history[Tick](eth_symbol, start_time, end_time, Resolution.TICK)

trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time, Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time, Resolution.MINUTE)
ticks_df = <?=$pyVar?>.history(eth_symbol, start_time, end_time, Resolution.TICK)
df = <?=$pyVar?>.history(btc_symbol, start_time, end_time, Resolution.HOUR)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-01-01 06:00:00</th><td>47188.00</td><td>47548.53</td><td>46669.76</td><td>46698.99</td><td>0.070150</td><td>47187.99</td><td>47546.38</td><td>46667.81</td><td>46696.14</td><td>0.254247</td><td>47188.00</td><td>47548.53</td><td>46668.64</td><td>46696.45</td><td>642.638402</td></tr>
    <tr><th></th><th>2022-01-01 07:00:00</th><td>46982.83</td><td>47329.31</td><td>46944.32</td><td>47188.00</td><td>0.017207</td><td>46977.97</td><td>47329.30</td><td>46944.31</td><td>47187.99</td><td>0.026489</td><td>46976.98</td><td>47327.89</td><td>46944.81</td><td>47188.00</td><td>380.560480</td></tr>
    <tr><th></th><th>2022-01-01 08:00:00</th><td>47200.57</td><td>47251.92</td><td>46905.43</td><td>46982.83</td><td>0.100000</td><td>47200.01</td><td>47251.90</td><td>46903.50</td><td>46977.97</td><td>0.000200</td><td>47200.01</td><td>47251.76</td><td>46903.50</td><td>46982.82</td><td>298.901283</td></tr>
    <tr><th></th><th>2022-01-01 09:00:00</th><td>47119.93</td><td>47349.54</td><td>47087.17</td><td>47200.57</td><td>0.101920</td><td>47119.92</td><td>47347.53</td><td>47087.16</td><td>47200.01</td><td>0.000016</td><td>47119.93</td><td>47347.54</td><td>47087.17</td><td>47200.14</td><td>215.983246</td></tr>
    <tr><th></th><th>2022-01-01 10:00:00</th><td>47139.67</td><td>47213.34</td><td>46937.35</td><td>47119.93</td><td>0.012300</td><td>47139.66</td><td>47210.63</td><td>46937.30</td><td>47119.92</td><td>0.043536</td><td>47139.67</td><td>47212.89</td><td>46938.37</td><td>47124.07</td><td>137.015047</td></tr>
  </tbody>
</table>
</div>


<pre class='csharp'><b>// EXAMPLE 6: Requesting By a Defined Period: 3 days of data with a specific resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(btcSymbol, startTime, endTime, Resolution.Daily);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(btcSymbol, startTime, endTime, Resolution.Minute);
var ticks = <?=$cVar?>History&lt;Tick&gt;(ethSymbol, startTime, endTime, Resolution.Tick);
var tradeBars2 = <?=$cVar?>History(btcSymbol, startTime, endTime, Resolution.Minute);</pre>
</div>

<h4>Multiple Symbol History Requests</h4>
<p>To request history for multiple symbols at a time, pass an array of <code>Symbol</code> objects to the same API methods shown in the preceding section. The return type of the method call depends on the history request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>. The following table describes the return type of each request <code class='python'>[Type]</code><code class='csharp'>&lt;Type&gt;</code>:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Request Type</th>
            <th>Return Data Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>No argument</td>
	    <td><code class='python'>DataFrame</code><code class='csharp'>List&lt;Slice&gt;</code></td>
        </tr>
        <tr>
	    <td><code>TradeBar</code></td>
	    <td><code class='python'>List[TradeBars]</code><code class='csharp'>List&lt;TradeBars&gt;</code></td>
        </tr>
        <tr>
	    <td><code>QuoteBar</code></td>
	    <td><code class='python'>List[QuoteBars]</code><code class='csharp'>List&lt;QuoteBars&gt;</code></td>
        </tr>
        <tr>
	    <td><code>Tick</code></td>
	    <td><code class='python'>List[Ticks]</code><code class='csharp'>List&lt;Ticks&gt;</code></td>
        </tr>
	<tr>
	    <td><code class='placeholder-text'>alternativeDataClass</code><br>(ex: <code>CBOE</code>)</td>
	    <td><span class='python'><code>List[dict[Symbol, <span class='placeholder-text'>alternativeDataClass</span>]]</code><br>(ex: <code>List[dict[Symbol, CBOE]]</code>)</span><span class='csharp'><code>List&lt;Dictionary&lt;Symbol, <span class='placeholder-text'>alternativeDataClass</span>&gt;&gt;</code><br>(ex: <code>List&lt;Dictionary&lt;Symbol, CBOE&gt;&gt;</code>)</span></td>
        </tr>
    </tbody>
</table>

<p class='csharp'>The <code>Slice</code> return type provides a container that supports all data types. For example, a history request for Forex <code>QuoteBars</code> and Equity <code>TradeBars</code> has the Forex data under <code>slices.QuoteBars</code> and the Equity data under <code>slices.Bars</code>.</p>

<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 7: Requesting By Bar Count for Multiple Symbols: 2 bars at the security resolution:</b>
vix = <?=$pyVar?>.add_data[CBOE]("VIX", Resolution.DAILY).symbol
v3m = <?=$pyVar?>.add_data[CBOE]("VIX3M", Resolution.DAILY).symbol
cboe_data = <?=$pyVar?>.history[CBOE]([vix, v3m], 2)

ibm = <?=$pyVar?>.add_equity("IBM", Resolution.MINUTE).symbol
aapl = <?=$pyVar?>.add_equity("AAPL", Resolution.MINUTE).symbol
trade_bars_list = <?=$pyVar?>.history[TradeBar]([ibm, aapl], 2)
quote_bars_list = <?=$pyVar?>.history[QuoteBar]([ibm, aapl], 2)

trade_bars_df = <?=$pyVar?>.history(TradeBar, [ibm, aapl], 2)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [ibm, aapl], 2)
df = <?=$pyVar?>.history([ibm, aapl], 2)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>IBM R735QTJ8XC9X</th><th>2022-07-08 15:59:00</th><td>140.50</td><td>140.63</td><td>140.45</td><td>140.62</td><td>500.0</td><td>140.48</td><td>140.62</td><td>140.44</td><td>140.61</td><td>100.0</td><td>140.49</td><td>140.61</td><td>140.45</td><td>140.61</td><td>39645.0</td></tr>
    <tr><th></th><th>2022-07-08 16:00:00</th><td>140.50</td><td>140.51</td><td>140.39</td><td>140.50</td><td>2200.0</td><td>140.48</td><td>140.50</td><td>140.35</td><td>140.48</td><td>500.0</td><td>140.47</td><td>140.50</td><td>140.35</td><td>140.48</td><td>542791.0</td></tr>
    <tr><th>AAPL R735QTJ8XC9X</th><th>2022-07-08 15:59:00</th><td>147.03</td><td>147.08</td><td>146.95</td><td>147.07</td><td>700.0</td><td>147.02</td><td>147.07</td><td>146.94</td><td>147.06</td><td>1300.0</td><td>147.02</td><td>147.08</td><td>146.94</td><td>147.06</td><td>500958.0</td></tr>
    <tr><th></th><th>2022-07-08 16:00:00</th><td>147.04</td><td>147.05</td><td>146.86</td><td>147.03</td><td>7100.0</td><td>147.03</td><td>147.03</td><td>146.84</td><td>147.02</td><td>300.0</td><td>147.04</td><td>147.06</td><td>146.84</td><td>147.02</td><td>4307608.0</td></tr>
  </tbody>
</table>
</div>

<pre class='csharp'><b>// EXAMPLE 7: Requesting By Bar Count for Multiple Symbols: 2 bars at the security resolution:</b>
var vixSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX", Resolution.Daily).Symbol;
var v3mSymbol = <?=$cVar?>AddData&lt;CBOE&gt;("VIX3m", Resolution.Daily).Symbol;
var cboeData = <?=$cVar?>History&lt;CBOE&gt;(new[] { vix, v3m }, 2);

var ibm = <?=$cVar?>AddEquity("IBM", Resolution.Minute).Symbol;
var aapl = <?=$cVar?>AddEquity("AAPL", Resolution.Minute).Symbol;
var tradeBarsList = <?=$cVar?>History&lt;TradeBar&gt;(new[] { ibm, aapl }, 2);
var quoteBarsList = <?=$cVar?>History&lt;QuoteBar&gt;(new[] { ibm, aapl }, 2);
</pre>
	
<pre class='python'><b># EXAMPLE 8: Requesting By Bar Count for Multiple Symbols: 5 bars with a specific resolution:</b>
trade_bars_list = <?=$pyVar?>.history[TradeBar]([ibm, aapl], 5, Resolution.DAILY)
quote_bars_list = <?=$pyVar?>.history[QuoteBar]([ibm, aapl], 5, Resolution.MINUTE)

trade_bars_df = <?=$pyVar?>.history(TradeBar, [ibm, aapl], 5, Resolution.DAILY)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [ibm, aapl], 5, Resolution.MINUTE)
df = <?=$pyVar?>.history([ibm, aapl], 5, Resolution.DAILY)  # Includes trade data only. No quote for daily equity data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>IBM R735QTJ8XC9X</th><th>2022-07-08</th><td>140.83</td><td>141.32</td><td>138.83</td><td>139.07</td><td>3834112.0</td></tr>
    <tr><th></th><th>2022-07-09</th><td>140.47</td><td>141.32</td><td>139.82</td><td>140.76</td><td>2763286.0</td></tr>
    <tr><th>AAPL R735QTJ8XC9X</th><th>2022-07-08</th><td>146.35</td><td>146.55</td><td>143.24</td><td>143.36</td><td>62554270.0</td></tr>
    <tr><th></th><th>2022-07-09</th><td>147.04</td><td>147.55</td><td>145.00</td><td>145.13</td><td>61610642.0</td></tr>
  </tbody>
</table>
</div>

<pre class='csharp'><b>// EXAMPLE 8: Requesting By Bar Count for Multiple Symbols: 5 bars with a specific resolution:</b>
var tradeBarsList = <?=$cVar?>History&lt;TradeBar&gt;(new[] { ibm, aapl }, 5, Resolution.Minute);
var quoteBarsList = <?=$cVar?>History&lt;QuoteBar&gt;(new[] { ibm, aapl }, 5, Resolution.Minute);
</pre>
	
	
<pre class='python'><b># EXAMPLE 9: Requesting By Trailing Period: 3 days of data at the security resolution:</b> 
ticks = <?=$pyVar?>.history[Tick]([eth_symbol], timedelta(days=3))

trade_bars = <?=$pyVar?>.history[TradeBar]([btc_symbol], timedelta(days=3)) 
quote_bars = <?=$pyVar?>.history[QuoteBar]([btc_symbol], timedelta(days=3))
trade_bars_df = <?=$pyVar?>.history(TradeBar, [btc_symbol], timedelta(days=3))
quote_bars_df = <?=$pyVar?>.history(QuoteBar, [btc_symbol], timedelta(days=3))
df = <?=$pyVar?>.history([btc_symbol], timedelta(days=3))  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-07-08 04:01:00</th><td>22141.16</td><td>22153.87</td><td>22116.38</td><td>22123.94</td><td>0.400000</td><td>22137.01</td><td>22153.86</td><td>22113.42</td><td>22123.93</td><td>0.136000</td><td>22144.89</td><td>22153.87</td><td>22116.38</td><td>22125.99</td><td>30.040683</td></tr>
    <tr><th></th><th>2022-07-08 04:02:00</th><td>22144.58</td><td>22165.07</td><td>22135.77</td><td>22141.16</td><td>0.057960</td><td>22144.57</td><td>22145.74</td><td>22132.86</td><td>22137.01</td><td>0.907468</td><td>22144.58</td><td>22164.97</td><td>22135.76</td><td>22140.51</td><td>30.609148</td></tr>
    <tr><th></th><th>2022-07-08 04:03:00</th><td>22121.26</td><td>22146.46</td><td>22118.96</td><td>22144.58</td><td>0.013358</td><td>22121.09</td><td>22146.45</td><td>22118.92</td><td>22144.57</td><td>0.001288</td><td>22121.78</td><td>22146.45</td><td>22118.96</td><td>22144.57</td><td>31.833847</td></tr>
    <tr><th></th><th>2022-07-08 04:04:00</th><td>22114.21</td><td>22121.26</td><td>22097.83</td><td>22121.26</td><td>0.002549</td><td>22114.20</td><td>22121.16</td><td>22096.63</td><td>22121.09</td><td>0.030212</td><td>22114.21</td><td>22121.18</td><td>22097.52</td><td>22121.09</td><td>10.173673</td></tr>
    <tr><th></th><th>2022-07-08 04:05:00</th><td>22108.03</td><td>22115.72</td><td>22097.83</td><td>22114.21</td><td>1.276832</td><td>22108.02</td><td>22114.31</td><td>22097.82</td><td>22114.20</td><td>0.000200</td><td>22108.02</td><td>22114.98</td><td>22097.82</td><td>22114.98</td><td>7.675039</td></tr>
  </tbody>
</table>
</div>
<pre class='csharp'><b>// EXAMPLE 9: Requesting By Trailing Period: 3 days of data at the security resolution:</b>
var ticks = <?=$cVar?>History&lt;Tick&gt;(new[] {ethSymbol}, TimeSpan.FromDays(3));

var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(new[] {btcSymbol}, TimeSpan.FromDays(3));
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(new[] {btcSymbol}, TimeSpan.FromDays(3));
var tradeBars2 = <?=$cVar?>History(new[] {btcSymbol}, TimeSpan.FromDays(3));</pre>	

<pre class='python'><b># EXAMPLE 10: Requesting By Defined Period: 3 days of data at the security resolution:</b> 
trade_bars = <?=$pyVar?>.history[TradeBar]([btc_symbol], start_time, end_time) 
quote_bars = <?=$pyVar?>.history[QuoteBar]([btc_symbol], start_time, end_time)
ticks = <?=$pyVar?>.history[Tick]([eth_symbol], start_time, end_time)
trade_bars_df = <?=$pyVar?>.history(TradeBar, btc_symbol, start_time, end_time)
quote_bars_df = <?=$pyVar?>.history(QuoteBar, btc_symbol, start_time, end_time)
ticks_df = <?=$pyVar?>.history(Tick, eth_symbol, start_time, end_time)
df = <?=$pyVar?>.history([btc_symbol], start_time, end_time)  # Includes trade and quote data
</pre>
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th><th></th><th>askclose</th><th>askhigh</th><th>asklow</th><th>askopen</th><th>asksize</th><th>bidclose</th><th>bidhigh</th><th>bidlow</th><th>bidopen</th><th>bidsize</th><th>close</th><th>high</th><th>low</th><th>open</th><th>volume</th>
    </tr>
    <tr>
      <th>symbol</th><th>time</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
    </tr>
  </thead>
  <tbody>
    <tr><th>BTCUSD XJ</th><th>2022-01-01 05:01:00</th><td>46724.00</td><td>46724.00</td><td>46682.98</td><td>46698.99</td><td>0.490830</td><td>46723.99</td><td>46723.99</td><td>46678.61</td><td>46696.14</td><td>0.002460</td><td>46724.00</td><td>46724.00</td><td>46678.61</td><td>46696.45</td><td>12.898891</td></tr>
    <tr><th></th><th>2022-01-01 05:02:00</th><td>46719.51</td><td>46742.76</td><td>46709.30</td><td>46724.00</td><td>0.000977</td><td>46719.50</td><td>46736.09</td><td>46708.37</td><td>46723.99</td><td>0.001225</td><td>46719.51</td><td>46742.76</td><td>46710.29</td><td>46723.99</td><td>19.193879</td></tr>
    <tr><th></th><th>2022-01-01 05:03:00</th><td>46713.32</td><td>46722.14</td><td>46697.04</td><td>46719.51</td><td>0.223169</td><td>46709.32</td><td>46719.50</td><td>46697.00</td><td>46719.50</td><td>0.043362</td><td>46713.31</td><td>46719.50</td><td>46697.04</td><td>46719.50</td><td>3.677070</td></tr>
    <tr><th></th><th>2022-01-01 05:04:00</th><td>46694.78</td><td>46715.84</td><td>46690.21</td><td>46713.32</td><td>0.065120</td><td>46694.77</td><td>46715.30</td><td>46688.76</td><td>46709.32</td><td>0.021412</td><td>46694.78</td><td>46715.84</td><td>46690.20</td><td>46709.33</td><td>3.759909</td></tr>
    <tr><th></th><th>2022-01-01 05:05:00</th><td>46693.46</td><td>46706.83</td><td>46690.03</td><td>46694.78</td><td>0.070000</td><td>46693.45</td><td>46701.72</td><td>46690.00</td><td>46694.77</td><td>0.008974</td><td>46693.37</td><td>46703.07</td><td>46690.00</td><td>46694.78</td><td>5.703675</td></tr>
  </tbody>
</table>
</div>
<pre class='csharp'><b>// EXAMPLE 10: Requesting By Defined Period: 3 days of data at the security resolution:</b>
var tradeBars = <?=$cVar?>History&lt;TradeBar&gt;(new[] {btcSymbol}, startTime, endTime);
var quoteBars = <?=$cVar?>History&lt;QuoteBar&gt;(new[] {btcSymbol}, startTime, endTime);
var ticks = <?=$cVar?>History&lt;Tick&gt;(new[] {ethSymbol}, startTime, endTime);
var tradeBars2 = <?=$cVar?>History(new[] {btcSymbol}, startTime, endTime);</pre>	

</div>

<p>If you request data for multiple securities and you use the <code class="csharp">Tick</code><code class="python">TICK</code> request type, each <code>Ticks</code> object in the list of results only contains the last tick of each security for that particular <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>timeslice</a>.</p>

<h4>All Symbol History Requests</h4>
 
<?=$writingAlgorithms ? "<p>You can request history for all active securities in your universe." : "<p>You can request history for all the securities you have created subscriptions for in your notebook session. "; ?> The parameters are very similar to other history method calls, but the return type is an array of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects. The <code>Slice</code> object holds all of the results in a sorted enumerable collection that you can iterate over with a loop.</p>
    
<div class='section-example-container'>
<pre class='python'><b># EXAMPLE 11: Requesting 5 bars for all securities at their respective resolution:</b>

# Create subscriptions
<?=$pyVar?>.add_equity("IBM", Resolution.DAILY)
<?=$pyVar?>.add_equity("AAPL", Resolution.DAILY)

# Request history data and enumerate results
slices = <?=$pyVar?>.history(5)
for s in slices:
    <?=$pyPrintMethod?>(str(s.time) + " AAPL:" + str(s.bars["AAPL"].close) + " IBM:" + str(s.bars["IBM"].close))
2022-07-02 00:00:00 AAPL:138.93 IBM:141.12
2022-07-06 00:00:00 AAPL:141.56 IBM:137.62
2022-07-07 00:00:00 AAPL:142.92 IBM:138.08
2022-07-08 00:00:00 AAPL:146.35 IBM:140.83
2022-07-09 00:00:00 AAPL:147.04 IBM:140.47
</pre>

<pre class='csharp'><b>// EXAMPLE 11: Requesting 5 bars for all securities at their respective resolution:</b>

// Set up the universe
<?=$cVar?>AddEquity("IBM", Resolution.Daily);
<?=$cVar?>AddEquity("AAPL", Resolution.Daily);

// Request history data and enumerate results:
var slices = <?=$cVar?>History(5);
foreach (var s in slices) {
    var aaplClose = s.Bars["AAPL"].Close;
    var ibmClose = s.Bars["IBM"].Close;
    <?=$cPrintMethod?>($"{s.Time} AAPL: {aaplClose} IBM: {ibmClose}");
}
7/2/2022 12:00:00 AM AAPL: 138.9300 IBM: 141.1200
7/6/2022 12:00:00 AM AAPL: 141.5600 IBM: 137.6200
7/7/2022 12:00:00 AM AAPL: 142.9200 IBM: 138.0800
7/8/2022 12:00:00 AM AAPL: 146.3500 IBM: 140.8300
7/9/2022 12:00:00 AM AAPL: 147.0400 IBM: 140.4700
</pre>
</div>

<div class='section-example-container'>  
<pre class='python'><b># EXAMPLE 12: Requesting 5 minutes for all securities:</b>

slices = <?=$pyVar?>.history(timedelta(minutes=5), Resolution.MINUTE)
for s in slices:
    <?=$pyPrintMethod?>(str(s.time) + " AAPL:" + str(s.bars["AAPL"].close) + " IBM:" + str(s.bars["IBM"].close))
2022-07-08 15:56:00 AAPL:147.2 IBM:140.71
2022-07-08 15:57:00 AAPL:147.125 IBM:140.67
2022-07-08 15:58:00 AAPL:147.065 IBM:140.61
2022-07-08 15:59:00 AAPL:147.02 IBM:140.49
2022-07-08 16:00:00 AAPL:147.04 IBM:140.47
# timedelta history requests are relative to "now" in <?=$envName?> Time. If you request this data at 16:05, it returns an empty array because the market is closed.</pre>
    
<pre class='csharp'><b>// EXAMPLE 12: Requesting 24 hours of hourly data for all securities:</b>

var slices = <?=$cVar?>History(TimeSpan.FromHours(24), Resolution.Hour);
foreach (var s in slices) {
    var aaplClose = s.Bars["AAPL"].Close;
    var ibmClose = s.Bars["IBM"].Close;
    <?=$cPrintMethod?>($"{s.Time} AAPL: {aaplClose} IBM: {ibmClose}");
}
7/8/2022 10:00:00 AM AAPL: 145.7600 IBM: 140.6300
7/8/2022 11:00:00 AM AAPL: 146.8600 IBM: 141.0400
7/8/2022 12:00:00 PM AAPL: 147.2500 IBM: 140.5700
7/8/2022 1:00:00 PM AAPL: 145.7900 IBM: 139.8500
7/8/2022 2:00:00 PM AAPL: 146.8500 IBM: 140.3300
7/8/2022 3:00:00 PM AAPL: 147.4550 IBM: 140.8400
7/8/2022 4:00:00 PM AAPL: 147.0400 IBM: 140.4700
// TimeSpan history requests are relative to "now" in <?=$envName?> Time.</pre>

</div>   

<h4>Assumed Default Values</h4>
<p>The following table describes the assumptions of the History API:</p>
<table class='table qc-table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Assumption</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Resolution</td>
            <td>LEAN guesses the resolution you request by looking at the securities you already have in your <?=$envName?>. If you have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, the history request uses the same resolution as the subscription. If you don't have a security subscription in your <?=$envName?> with a matching <code>Symbol</code>, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is the default.</td>
        </tr>
        <tr class='csharp'>
            <td>Bar type</td>
            <td>If you don't specify a type for the history request, <code>TradeBar</code> is the default. If the asset you request data for doesn't have <code>TradeBar</code> data, specify the <code>QuoteBar</code> type to receive history.</td>
        </tr>
    </tbody>
</table>

<h4>Additional Options</h4>
<p>The <code class="csharp">History</code><code class="python">history</code> method accepts the following additional arguments:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">fillForward</code><code class="python">fill_forward</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to <a href='/docs/v2/writing-algorithms/securities/requesting-data#05-Fill-Forward'>fill forward</a> missing data. Otherwise, false. If you don't provide a value, it uses the fill forward mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to include extended market hours data. Otherwise, false.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code></td>
	        <td><code class='csharp'>DataMappingMode?</code><code class='python'>DataMappingMode/NoneType</code></td>
            <td>The <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>contract mapping mode</a> to use for the security history request.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code></td>
            <td><code class='csharp'>DataNormalizationMode?</code><code class='python'>DataNormalizationMode/NoneType</code></td>
            <td>The price scaling mode to use for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>US Equities</a> or <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>. If you don't provide a value, it uses the data normalization mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code></td>
            <td><code class='csharp'>int?</code><code class='python'>int/NoneType</code></td>
            <td>The desired offset from the current front month for <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>

<div class='section-example-container'>
    <pre class='python'><?=$pyFutureVar?>future = <?=$pyVar?>.add_future(Futures.Currencies.BTC)
history = <?=$pyVar?>.history(
    tickers=[<?=$pyFutureVar?>future.symbol], 
    start=<?=$pyVar?>.time - timedelta(days=15), 
    end=<?=$pyVar?>.time, 
    resolution=Resolution.MINUTE, 
    fill_forward=False, 
    extended_market_hours=False, 
    dataMappingMode=DataMappingMode.OPEN_INTEREST, 
    dataNormalizationMode=DataNormalizationMode.RAW, 
    contractDepthOffset=0)</pre>
    <pre class='csharp'>var future = <?=$cVar?>AddFuture(Futures.Currencies.BTC);
var history = <?=$cVar?>History(
    symbols: new[] {future.Symbol}, 
    start: <?=$cVar?>Time - TimeSpan.FromDays(15),
    end: <?=$cVar?>Time,
    resolution: Resolution.Minute,
    fillForward: false,
    extendedMarketHours: false,
    dataMappingMode: DataMappingMode.OpenInterest,
    dataNormalizationMode: DataNormalizationMode.Raw,
    contractDepthOffset: 0);</pre>
</div>
