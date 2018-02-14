public class IndicatorTests : QCAlgorithm
{
    //Save off reference to indicator objects
    RelativeStrengthIndex _rsi;
    SimpleMovingAverage _rsiSMA;

    public override void Initialize()
    {
       //In addition to other initialize logic:
       _rsi = RSI("SPY", 14); // Creating a RSI
       _rsiSMA = _rsi.SMA(3); // Creating the SMA on the RSI
    }
    
    public override void OnData(Slice data)
    {
       Plot("RSI", _rsi, _rsiSMA);
    }
}