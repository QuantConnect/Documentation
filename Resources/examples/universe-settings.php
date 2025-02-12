<h4>Example <?=$number?>: Weekly-Updating Liquid Universe</h4>
<p>The following algorithm demonstrates daily EMA cross, trading on the most liquid stocks. The universe is set to be updated weekly. Various universe settings have been set to simulate the brokerage environment best and for trading needs.</p>
<div class="section-example-container to-be-tested">
    <pre class="csharp">public class UniverseSettingsAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2021, 2, 1);
        
        // To avoid over-updating the universe, which causes insufficient time to earn the trend, update the universe weekly.
        UniverseSettings.Schedule.On(DateRules.WeekStart());
        UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
        // Rebalancing frequency is daily, so we only need to subscribe to daily resolution.
        UniverseSettings.Resolution = Resolution.Daily;
        // To best simulate IB's margin requirement set the leverage to 50%.
        UniverseSettings.Leverage = 2;
        // Since we trade by market order, we cannot use extended market hours data to fill.
        UniverseSettings.ExtendedMarketHours = false;
        // We want to trade the EMA with raw price but not altered by splits.
        UniverseSettings.DataNormalizationMode = DataNormalizationMode.SplitAdjusted;

        <? if ($framework) { ?>
        // Select and trade the top liquid universe.
        AddUniverseSelection(new QC500UniverseSelectionModel());
        <? } else { ?>
        // Only trade on the top 10 most traded stocks since they have the most popularity to drive trends.
        AddUniverse(Universe.DollarVolume.Top(10));
        <? } ?>
    }

    public override void OnData(Slice slice)
    {
        foreach (var (symbol, bar) in slice.Bars)
        {
            // Trade the trend by EMA crossing.
            var ema = (Securities[symbol] as dynamic).ema;
            if (bar.Close &gt; ema)
            {
                SetHoldings(symbol, 0.05m);
            }
            else
            {
                SetHoldings(symbol, -0.05m);
            }
        }
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var removed in changes.RemovedSecurities)
        {
            // Liquidate the ones leaving the universe since we will not trade their EMA anymore.
            Liquidate(removed.Symbol);
            // Deregister the EMA indicator to free resources
            DeregisterIndicator((removed as dynamic).ema as ExponentialMovingAverage);
        }

        foreach (var added in changes.AddedSecurities)
        {
            var security = added as dynamic;
            // Create EMA indicator for trend trading signals.
            security.ema = EMA(added.Symbol, 50, Resolution.Daily);
            // Warm up the EMA indicator to ensure its readiness for immediate use.
            WarmUpIndicator(added.Symbol, (ExponentialMovingAverage)security.ema, Resolution.Daily);
        }
    }
}</pre>
    <pre class="python">class UniverseSettingsAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2021, 1, 1)
        self.set_end_date(2021, 2, 1)
        
        # To avoid over-updating the universe, which causes insufficient time to earn the trend, update the universe weekly.
        self.universe_settings.schedule.on(self.date_rules.week_start())
        self.universe_settings.minimum_time_in_universe = timedelta(7)
        # Rebalancing frequency is daily, so we only need to subscribe to daily resolution.
        self.universe_settings.resolution = Resolution.DAILY
        # To best simulate IB's margin requirement, set the leverage to 50%.
        self.universe_settings.leverage = 2
        # Since we trade by market order, we cannot use extended market hours data to fill.
        self.universe_settings.extended_market_hours = False
        # We want to trade the EMA with raw price but not altered by splits.
        self.universe_settings.data_normalization_mode = DataNormalizationMode.SPLIT_ADJUSTED

        <? if ($framework) { ?>
        # Select and trade the top liquid universe.
        self.add_universe_selection(QC500UniverseSelectionModel())
        <? } else { ?>
        # Only trade on the top 10 most traded stocks since they have the most popularity to drive trends.
        self.add_universe(self.universe.dollar_volume.top(10))
        <? } ?>

    def on_data(self, slice: Slice) -&gt; None:
        for symbol, bar in slice.bars.items():
            # Trade the trend by EMA crossing.
            ema = self.securities[symbol].ema.current.value
            if bar.close &gt; ema:
                self.set_holdings(symbol, 0.05)
            else:
                self.set_holdings(symbol, -0.05)

    def on_securities_changed(self, changes: SecurityChanges) -&gt; None:
        for removed in changes.removed_securities:
            # Liquidate the ones leaving the universe since we will not trade their EMA anymore.
            self.liquidate(removed.symbol)
            # Deregister the EMA indicator to free resources
            self.deregister_indicator(removed.ema)

        for added in changes.added_securities:
            # Create EMA indicator for trend trading signals.
            added.ema = self.ema(added.symbol, 50, Resolution.DAILY)
            # Warm up the EMA indicator to ensure its readiness for immediate use.
            self.warm_up_indicator(added.symbol, added.ema, Resolution.DAILY)</pre>
</div>
