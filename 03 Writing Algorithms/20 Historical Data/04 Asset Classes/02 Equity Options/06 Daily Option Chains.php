<p class='csharp'>
  To get historical <a href="/docs/v2/writing-algorithms/universes/equity-options#08-Navigate-Daily-Option-Chains">daily Option chain data</a>, call the <code>History</code> method with the Option <code>Symbol</code> object.
  The data this method returns contains information on all the currently tradable contracts, not just the contracts that pass your filter.
</p>

<p class='python'>
  To get historical <a href="/docs/v2/writing-algorithms/universes/equity-options#08-Navigate-Daily-Option-Chains">daily Option chain data</a>, call the <code>history</code> method with the Option <code>Symbol</code> object.
  The data this method returns contains information on all the currently tradable contracts, not just the contracts that pass your filter.
  If you pass <code>flatten=True</code>, this method returns a DataFrame with columns for the data point attributes.
</p>

<div class="section-example-container">
    <pre class="csharp">public class EquityOpionDailyOptionChainHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 23);
        // Add a universe of US Equities based on the constituents of an ETF.
        var universe = AddUniverse(Universe.ETF("SPY"));
        // Get 5 days of history for the universe.
        var history = History(universe, TimeSpan.FromDays(5));
        // Iterate through each day of the universe history.
        foreach (var constituents in history)
        {
            // Select the 2 assets with the smallest weights in the ETF on this day.
            var dailyLargest = constituents.Select(c => c as ETFConstituentData).OrderByDescending(c => c.Weight).Take(2);
        }
    }
}</pre>
    <pre class="python">class EquityOpionDailyOptionChainHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 23)
        # Add an Equity Option universe.
        option = self.add_option('SPY')
        # Get the trailing 5 daily Option chains in DataFrame format.
        history = self.history(universe, 5, flatten=True)</pre>
</div>

<div class="dataframe-wrapper">
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>close</th>
      <th>delta</th>
      <th>gamma</th>
      <th>high</th>
      <th>impliedvolatility</th>
      <th>low</th>
      <th>open</th>
      <th>openinterest</th>
      <th>rho</th>
      <th>theta</th>
      <th>underlying</th>
      <th>value</th>
      <th>vega</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>time</th>
      <th>symbol</th>
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
      <th rowspan="5" valign="top">2024-12-13</th>
      <th>SPY YOGVNNCO8QDI|SPY R735QTJ8XC9X</th>
      <td>484.815</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>487.945</td>
      <td>3.212331</td>
      <td>484.225</td>
      <td>486.74</td>
      <td>1539.0</td>
      <td>0.000000</td>
      <td>0.000000</td>
      <td>SPY: ¤604.33</td>
      <td>484.815</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY YOGVNNCU72FA|SPY R735QTJ8XC9X</th>
      <td>474.210</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>477.970</td>
      <td>3.055466</td>
      <td>474.210</td>
      <td>476.91</td>
      <td>26.0</td>
      <td>0.000000</td>
      <td>0.000000</td>
      <td>SPY: ¤604.33</td>
      <td>474.210</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY YOGVNND05EH2|SPY R735QTJ8XC9X</th>
      <td>464.835</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>467.965</td>
      <td>2.910873</td>
      <td>464.230</td>
      <td>466.91</td>
      <td>4.0</td>
      <td>0.000000</td>
      <td>0.000000</td>
      <td>SPY: ¤604.33</td>
      <td>464.835</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY YOGVNND63QIU|SPY R735QTJ8XC9X</th>
      <td>454.845</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>458.000</td>
      <td>2.775398</td>
      <td>454.245</td>
      <td>456.92</td>
      <td>18.0</td>
      <td>0.000000</td>
      <td>0.000000</td>
      <td>SPY: ¤604.33</td>
      <td>454.845</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY YTG30NXW11QE|SPY R735QTJ8XC9X</th>
      <td>456.345</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>459.235</td>
      <td>0.687831</td>
      <td>456.270</td>
      <td>458.77</td>
      <td>33.0</td>
      <td>0.000000</td>
      <td>0.000000</td>
      <td>SPY: ¤604.33</td>
      <td>456.345</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
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
    </tr>
    <tr>
      <th rowspan="5" valign="top">2024-12-19</th>
      <th>SPY 33899RRUZK23Q|SPY R735QTJ8XC9X</th>
      <td>314.995</td>
      <td>-0.865013</td>
      <td>0.000496</td>
      <td>317.045</td>
      <td>0.145628</td>
      <td>293.900</td>
      <td>297.42</td>
      <td>1.0</td>
      <td>-5.866155</td>
      <td>0.038392</td>
      <td>SPY: ¤586.28</td>
      <td>314.995</td>
      <td>0.573632</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY 337HP99QFUJJA|SPY R735QTJ8XC9X</th>
      <td>319.995</td>
      <td>-0.874953</td>
      <td>0.000412</td>
      <td>321.765</td>
      <td>0.142599</td>
      <td>299.020</td>
      <td>302.42</td>
      <td>0.0</td>
      <td>-5.754009</td>
      <td>0.039155</td>
      <td>SPY: ¤586.28</td>
      <td>319.995</td>
      <td>0.452253</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY 33899RVZ5ZO12|SPY R735QTJ8XC9X</th>
      <td>319.990</td>
      <td>-0.868866</td>
      <td>0.000447</td>
      <td>321.805</td>
      <td>0.143224</td>
      <td>298.995</td>
      <td>302.47</td>
      <td>0.0</td>
      <td>-5.954058</td>
      <td>0.039088</td>
      <td>SPY: ¤586.28</td>
      <td>319.990</td>
      <td>0.511173</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY 337HP95ZTK8HY|SPY R735QTJ8XC9X</th>
      <td>324.995</td>
      <td>-0.876073</td>
      <td>0.000389</td>
      <td>326.715</td>
      <td>0.142820</td>
      <td>304.030</td>
      <td>307.43</td>
      <td>0.0</td>
      <td>-5.842472</td>
      <td>0.040096</td>
      <td>SPY: ¤586.28</td>
      <td>324.995</td>
      <td>0.428058</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPY 33899RS8JPCZQ|SPY R735QTJ8XC9X</th>
      <td>324.995</td>
      <td>-0.868426</td>
      <td>0.000446</td>
      <td>326.745</td>
      <td>0.145502</td>
      <td>303.975</td>
      <td>307.46</td>
      <td>1.0</td>
      <td>-6.048355</td>
      <td>0.039695</td>
      <td>SPY: ¤586.28</td>
      <td>324.995</td>
      <td>0.517696</td>
      <td>0.0</td>
    </tr>
  </tbody>
</table>
</div>


<div class="python section-example-container">
    <pre class="python"># Select the 2 contracts with the greatest volume each day.
most_traded = history.groupby('time').apply(lambda x: x.nlargest(2, 'volume')).reset_index(level=1, drop=True).volume</pre>
</div>

<div class="python section-example-container">
    <pre>time        symbol                            
2024-12-13  SPY 32NDZOIHRHLRA|SPY R735QTJ8XC9X    166689.0
            SPY 32NDZOIHXFXT2|SPY R735QTJ8XC9X    160265.0
2024-12-14  SPY YOCXVCLWHPT2|SPY R735QTJ8XC9X     137597.0
            SPY YOCXVCM2G1UU|SPY R735QTJ8XC9X     100401.0
2024-12-17  SPY 32NHXGVYLQYG6|SPY R735QTJ8XC9X    104700.0
            SPY YODXBFZKFH46|SPY R735QTJ8XC9X     103635.0
2024-12-18  SPY 32NIWWZBFX1IE|SPY R735QTJ8XC9X    106804.0
            SPY YOEWRJCELK6E|SPY R735QTJ8XC9X      75769.0
2024-12-19  SPY 32NKVT60AHJDY|SPY R735QTJ8XC9X    109298.0
            SPY YOFW7MPKOBC6|SPY R735QTJ8XC9X      94823.0
Name: volume, dtype: float64</pre>
</div>

<p class='python'>
  To get the data in the format of <code>OptionUniverse</code> object instead of a DataFrame, call the <code>history[OptionUniverse]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the historical OptionUniverse data over the last 30 days.
history = self.history[OptionUniverse](option.symbol, timedelta(30))
# Iterate through each daily Option chain.
for option_universe in history:
    t = option_universe.end_time
    # Select the contract with the most volume.
    most_traded = sorted(option_universe, key=lambda contract: contract.volume)[-1]</pre>
</div>
