<p>
  The <code>Selected</code> property of your <code>Universe</code> contains references to all the assets that are currently in the universe.
  The <code>Universe.Selected</code> property differs from the <code>Universe.Members</code> property because the <code>Universe.Members</code> property can contain more assets than <code>Universe.Selected</code>.
  The <code>QCAlgorithm.ActiveSecurities</code> is a collection of <code>Universe.Members</code> of all universes.
  To access the <code>Universe</code> object, save a reference to the result of the <code>AddUniverse</code> method.
  The following algorithm demonstrates how to use the <code>Universe.Selected</code> property to create simple rebalancing strategies:
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

    def Initialize(self):
        symbol = Symbol.Create("SPY", SecurityType.Equity, Market.USA)
        date_rule = self.DateRules.WeekStart(symbol)
        self.UniverseSettings.Schedule.On(date_rule)
        universe = self.AddUniverse(self.Universe.DollarVolume.Top(5))
        self.Schedule.On(
            date_rule,
            self.TimeRules.AfterMarketOpen(symbol, 30),
            lambda: self.SetHoldings(
                [PortfolioTarget(symbol, 1/len(universe.Selected)) for symbol in universe.Selected], 
                True
            )
        )</pre>
</div>
