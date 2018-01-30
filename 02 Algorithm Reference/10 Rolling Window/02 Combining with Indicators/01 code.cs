// In Initialize, create the rolling windows
public override void Initialize()
{
    // Creates an indicator and adds to a rolling window when it is updated
    SMA("SPY", 5).Updated += (sender, updated) => _smaWin.Add(updated);
    _smaWin = new RollingWindow<IndicatorDataPoint>(5);
}