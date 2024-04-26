<p>
  The <code class="csharp">Selected</code><code class="python">selected</code> property of your <code>Universe</code> contains references to all the assets that are currently in the universe.
  The <code class="csharp">Universe.Selected</code><code class="python">Universe.selected</code> property differs from the <code class="csharp">Universe.Members</code><code class="python">Universe.members</code> property because the <code class="csharp">Universe.Members</code><code class="python">Universe.members</code> property can contain more assets than <code class="csharp">Universe.Selected</code><code class="python">Universe.selected</code>.
  The <code class="csharp">QCAlgorithm.ActiveSecurities</code><code class="python">QCAlgorithm.active_securities</code> is a collection of <code class="csharp">Universe.Members</code><code class="python">Universe.members</code> of all universes.
  To access the <code>Universe</code> object, save a reference to the result of the <code class="csharp">AddUniverse</code><code class="python">add_universe</code> method.
  The following algorithm demonstrates how to use the <code class="csharp">Universe.Selected</code><code class="python">Universe.selected</code> property to create simple rebalancing strategies:
</p>

 <div class="section-example-container">
<pre class="csharp">public class SimpleRebalancingAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        var symbol = QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA);
        var dateRule = DateRules.WeekStart(symbol);
        UniverseSettings.Schedule.On(dateRule);
        var universe = AddUniverse(Universe.DollarVolume.Top(5));
        Schedule.On(
            dateRule,
            TimeRules.AfterMarketOpen(symbol, 30),
            () => SetHoldings(
                universe.Selected.Select(symbol => new PortfolioTarget(symbol, 1.0m/universe.Selected.Count)).ToList(),
                true
            )
        );
    }
}</pre>
<pre class="python">class SimpleRebalancingAlgorithm(QCAlgorithm):

    def initialize(self):
        symbol = Symbol.create("SPY", SecurityType.EQUITY, Market.USA)
        date_rule = self.date_rules.week_start(symbol)
        self.universe_settings.schedule.on(date_rule)
        universe = self.add_universe(self.universe.dollar_volume.top(5))
        self.schedule.on(
            date_rule,
            self.time_rules.after_market_open(symbol, 30),
            lambda: self.set_holdings(
                [PortfolioTarget(symbol, 1/len(universe.selected)) for symbol in universe.selected], 
                True
            )
        )</pre>
</div>
