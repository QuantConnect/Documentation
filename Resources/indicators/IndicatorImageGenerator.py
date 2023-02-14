# region imports
from telnetlib import SE
from AlgorithmImports import *
from QuantConnect.Indicators.CandlestickPatterns import *
import plotly.express as px
# endregion

def generate(name, indicator, df):
    columns = set(df.columns).intersection(indicator.get('columns', '') + ['price'])
    df = df.drop(columns=columns).dropna()
    fig = px.line(df, x=df.index, y=df.columns, title=indicator['title'])
    fig.write_image(f"{name}.png", width=1200, height=630, format='png')

class IndicatorImageGeneratorAlgorithm(QCAlgorithm):


    def Initialize(self):
        self.SetStartDate(2021,1,1)

        qb = QuantBook()
        qb.AddEquity("SPY", Resolution.Daily)
        qb.AddEquity("QQQ", Resolution.Daily)
        qb.SetStartDate(self.StartDate)

        indicators = {
            'bollinger-bands':
            {
                'code': BollingerBands(30, 2),
                'title' : 'BB("SPY", 30, 2)',
                'columns' : ['bandwidth','percentb','standarddeviation']
            },
            'absolute-price-oscillator':
            {
                'code': AbsolutePriceOscillator(10, 20, MovingAverageType.Simple),
                'title' : 'APO("SPY", 10, 2, MovingAverageType.Simple)',
                'columns' : ['fast','slow']
            },
            'acceleration-bands':
            {
                'code': AccelerationBands("", 10, 4, MovingAverageType.Simple),
                'title' : 'ABANDS("SPY", 10, 4, MovingAverageType.Simple)',
                'columns' : []
            },
            'accumulation-distribution':
            {
                'code': AccumulationDistribution(),
                'title' : 'AD("SPY")',
                'columns' : []
            },
            'accumulation-distribution-oscillator':
            {
                'code': AccumulationDistributionOscillator(10, 20),
                'title' : 'ADOSC("SPY", 10, 2)',
                'columns' : []
            },
            'arnaud-legoux-moving-average':
            {
                'code': ArnaudLegouxMovingAverage(10, 6, 0.85),
                'title' : 'ALMA("SPY", 10, 6, 0.85)',
                'columns' : []
            },
            'aroon-oscillator':
            {
                'code': AroonOscillator(10, 20),
                'title' : 'AROON("SPY", 10, 20)',
                'columns' : []
            },
            'augen-price-spike':
            {
                'code': AugenPriceSpike(3),
                'title' : 'APS("SPY", 3)',
                'columns' : []
            },
            'auto-regressive-integrated-moving-average':
            {
                'code': AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, True),
                'title' : 'ARIMA("SPY", 1, 1, 1, 20)',
                'columns' : []
            },
            'average-directional-index':
            {
                'code': AverageDirectionalIndex(20),
                'title' : 'ADX("SPY", 20)',
                'columns' : []
            },
            'average-directional-movement-index-rating':
            {
                'code': AverageDirectionalMovementIndexRating(20),
                'title' : 'ADXR("SPY", 20)',
                'columns' : []
            },
            'average-true-range':
            {
                'code': AverageTrueRange(20, MovingAverageType.Simple),
                'title' : 'ATR("SPY", 20, MovingAverageType.Simple)',
                'columns' : []
            },
            'awesome-oscillator':
            {
                'code': AwesomeOscillator(10, 20, MovingAverageType.Simple),
                'title' : 'AO("SPY", 10, 20, MovingAverageType.Simple)',
                'columns' : ['fastao', 'slowao']
            },
            'balance-of-power':
            {
                'code': BalanceOfPower(),
                'title' : 'BOP("SPY")',
                'columns' : []
            },
            'chaikin-money-flow':
            {
                'code': ChaikinMoneyFlow("SPY", 20),
                'title' : 'CMF("SPY", 20)',
                'columns' : []
            },
            'chande-momentum-oscillator':
            {
                'code': ChandeMomentumOscillator(20),
                'title' : 'CMO("SPY", 20)',
                'columns' : []
            },
            'commodity-channel-index':
            {
                'code': CommodityChannelIndex(20, MovingAverageType.Simple),
                'title' : 'CCI("SPY", 20, MovingAverageType.Simple)',
                'columns' : ['typicalpriceaverage', 'typicalpricemeandeviation']
            },
            'coppock-curve':
            {
                'code': CoppockCurve(11, 14, 10),
                'title' : 'CC("SPY", 11, 14, 10)',
                'columns' : []
            },
            'de-marker-indicator':
            {
                'code': DeMarkerIndicator(20, MovingAverageType.Simple),
                'title' : 'DEM("SPY", 20, MovingAverageType.Simple)',
                'columns' : []
            },
            'detrended-price-oscillator':
            {
                'code': DetrendedPriceOscillator(20),
                'title' : 'DPO("SPY", 20)',
                'columns' : []
            },
            'donchian-channel':
            {
                'code': DonchianChannel(20, 20),
                'title' : 'DCH("SPY", 20, 20)',
                'columns' : []
            },
            'double-exponential-moving-average':
            {
                'code': DoubleExponentialMovingAverage(20),
                'title' : 'DEMA("SPY", 20)',
                'columns' : []
            },
            'ease-of-movement-value':
            {
                'code': EaseOfMovementValue(1, 10000),
                'title' : 'EMV("SPY", 1, 10000)',
                'columns' : []
            },
            'exponential-moving-average':
            {
                'code': ExponentialMovingAverage(20, 0.5),
                'title' : 'EMA("SPY", 20, 0.5)',
                'columns' : []
            },
            'fisher-transform':
            {
                'code': FisherTransform(20),
                'title' : 'FISH("SPY", 20)',
                'columns' : []
            },
            'fractal-adaptive-moving-average':
            {
                'code': FractalAdaptiveMovingAverage(20, 198),
                'title' : 'FRAMA("SPY", 20, 198)',
                'columns' : []
            },
            'heikin-ashi':
            {
                'code': HeikinAshi(),
                'title' : 'HeikinAshi("SPY")',
                'columns' : ['volume']
            },
            'hull-moving-average':
            {
                'code': HullMovingAverage(20),
                'title' : 'HMA("SPY", 20)',
                'columns' : []
            },
            'ichimoku-kinko-hyo':
            {
                'code': IchimokuKinkoHyo(9, 26, 17, 52, 26, 26),
                'title' : 'ICHIMOKU("SPY", 9, 26, 17, 52, 26, 26)',
                'columns' : ['chikou']
            },
            'identity':
            {
                'code': Identity("SPY"),
                'title' : 'Identity("SPY")',
                'columns' : []
            },
            'kaufman-adaptive-moving-average':
            {
                'code': KaufmanAdaptiveMovingAverage(20, 10, 20),
                'title' : 'KAMA("SPY", 20, 10, 20)',
                'columns' : []
            },
            'kaufman-efficiency-ratio':
            {
                'code': KaufmanEfficiencyRatio(20),
                'title' : 'KER("SPY", 20)',
                'columns' : []
            },
            'keltner-channels':
            {
                'code': KeltnerChannels(20, 2, MovingAverageType.Simple),
                'title' : 'KCH("SPY", 20, 2, MovingAverageType.Simple)',
                'columns' : ['averagetruerange']
            },
            'least-squares-moving-average':
            {
                'code': LeastSquaresMovingAverage(20),
                'title' : 'LSMA("SPY", 20)',
                'columns' : ['slope']
            },
            'linear-weighted-moving-average':
            {
                'code': LinearWeightedMovingAverage(20),
                'title' : 'LWMA("SPY", 20)',
                'columns' : []
            },
            'log-return':
            {
                'code': LogReturn(20),
                'title' : 'LOGR("SPY", 20)',
                'columns' : []
            },
            'mass-index':
            {
                'code': MassIndex(9, 25),
                'title' : 'MASS("SPY", 9, 25)',
                'columns' : []
            },
            'maximum':
            {
                'code': Maximum(20),
                'title' : 'MAX("SPY", 20)',
                'columns' : []
            },
            'mid-point':
            {
                'code': MidPoint(20),
                'title' : 'MIDPOINT("SPY", 20)',
                'columns' : []
            },
            'mid-price':
            {
                'code': MidPrice(20),
                'title' : 'MIDPRICE("SPY", 20)',
                'columns' : []
            },
            'mean-absolute-deviation':
            {
                'code': MeanAbsoluteDeviation(20),
                'title' : 'MAD("SPY", 20)',
                'columns' : ['mean']
            },
            'minimum':
            {
                'code': Minimum(20),
                'title' : 'MIN("SPY", 20)',
                'columns' : []
            },
            'momentum':
            {
                'code': Momentum(20),
                'title' : 'MOM("SPY", 20)',
                'columns' : []
            },
            'momentum-percent':
            {
                'code': MomentumPercent(20),
                'title' : 'MOMP("SPY", 20)',
                'columns' : []
            },
            'momersion-indicator':
            {
                'code': MomersionIndicator(10, 20),
                'title' : 'MOMERSION("SPY", 10, 20)',
                'columns' : []
            },
            'money-flow-index':
            {
                'code': MoneyFlowIndex(20),
                'title' : 'MFI("SPY", 20)',
                'columns' : ['positivemoneyflow', 'negativemoneyflow', 'previoustypicalprice']
            },
            'moving-average-convergence-divergence':
            {
                'code': MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.Exponential),
                'title' : 'MACD("SPY", 12, 26, 9, MovingAverageType.Exponential)',
                'columns' : ['fast', 'slow']
            },
            'normalized-average-true-range':
            {
                'code': NormalizedAverageTrueRange(20),
                'title' : 'NATR("SPY", 20)',
                'columns' : []
            },
            'on-balance-volume':
            {
                'code': OnBalanceVolume(),
                'title' : 'OBV("SPY")',
                'columns' : []
            },
            'parabolic-stop-and-reverse':
            {
                'code': ParabolicStopAndReverse(0.02, 0.02, 0.2),
                'title' : 'PSAR("SPY", 0.02, 0.02, 0.2)',
                'columns' : []
            },
            'percentage-price-oscillator':
            {
                'code': PercentagePriceOscillator(10, 20, MovingAverageType.Simple),
                'title' : 'PPO("SPY", 10, 20, MovingAverageType.Simple)',
                'columns' : ['fast', 'slow']
            },
            'pivot-points-high-low':
            {
                'code': PivotPointsHighLow(10, 10, 100),
                'title' : 'PPHL("SPY", 10, 10, 100)',
                'columns' : []
            },
            'rate-of-change':
            {
                'code': RateOfChange(10),
                'title' : 'ROC("SPY", 10)',
                'columns' : []
            },
            'rate-of-change-percent':
            {
                'code': RateOfChangePercent(10),
                'title' : 'ROCP("SPY", 10)',
                'columns' : []
            },
            'rate-of-change-ratio':
            {
                'code': RateOfChangeRatio(10),
                'title' : 'ROCR("SPY", 10)',
                'columns' : []
            },
            'regression-channel':
            {
                'code': RegressionChannel(20, 2),
                'title' : 'RC("SPY", 20, 2)',
                'columns' : ['slope']
            },
            'relative-daily-volume':
            {
                'code': RelativeDailyVolume(2),
                'title' : 'RDV("SPY", 2)',
                'columns' : []
            },
            'relative-moving-average':
            {
                'code': RelativeMovingAverage(20),
                'title' : 'RMA("SPY", 20)',
                'columns' : ['shortaverage', 'mediumaverage', 'longaverage']
            },
            'relative-strength-index':
            {
                'code': RelativeStrengthIndex(14),
                'title' : 'RSI("SPY", 14)',
                'columns' : ['averagegain', 'averageloss']
            },
            'relative-vigor-index':
            {
                'code': RelativeVigorIndex(20, MovingAverageType.Simple),
                'title' : 'RVI("SPY", 20, MovingAverageType.Simple)',
                'columns' : []
            },
            'schaff-trend-cycle':
            {
                'code': SchaffTrendCycle(5, 10, 20, MovingAverageType.Exponential),
                'title' : 'STC("SPY", 5, 10, 20, MovingAverageType.Exponential)',
                'columns' : []
            },
            'sharpe-ratio':
            {
                'code': SharpeRatio(22, 0.03),
                'title' : 'SR("SPY", 22, 0.03)',
                'columns' : []
            },
            'simple-moving-average':
            {
                'code': SimpleMovingAverage(20),
                'title' : 'SMA("SPY", 20)',
                'columns' : ['rollingsum']
            },
            'sortino-ratio':
            {
                'code': SortinoRatio(22),
                'title' : 'SORTINO("SPY", 22)',
                'columns' : []
            },
            'standard-deviation':
            {
                'code': StandardDeviation(22),
                'title' : 'STD("SPY", 22)',
                'columns' : []
            },
            'stochastic':
            {
                'code': Stochastic(20, 10, 20),
                'title' : 'STO("SPY", 20, 10, 20)',
                'columns' : []
            },
            'sum':
            {
                'code': Sum(20),
                'title' : 'SUM("SPY", 20)',
                'columns' : []
            },
            'super-trend':
            {
                'code': SuperTrend(20, 2, MovingAverageType.Wilders),
                'title' : 'STR("SPY", 20, 2, MovingAverageType.Wilders)',
                'columns' : ['basicupperband', 'basiclowerband', 'currenttrailingupperband', 'currenttrailinglowerband']
            },
            'swiss-army-knife':
            {
                'code': SwissArmyKnife(20, 0.2, SwissArmyKnifeTool.Gauss),
                'title' : 'SWISS("SPY", 20, 0.2, SwissArmyKnifeTool.Gauss)',
                'columns' : []
            },
            't3-moving-average':
            {
                'code': T3MovingAverage(30, 0.7),
                'title' : 'T3("SPY", 30, 0.7)',
                'columns' : []
            },
            'triangular-moving-average':
            {
                'code': TriangularMovingAverage(20),
                'title' : 'TRIMA("SPY", 20)',
                'columns' : []
            },
            'triple-exponential-moving-average':
            {
                'code': TripleExponentialMovingAverage(20),
                'title' : 'TEMA("SPY", 20)',
                'columns' : []
            },
            'trix':
            {
                'code': Trix(20),
                'title' : 'TRIX("SPY", 20)',
                'columns' : []
            },
            'true-range':
            {
                'code': TrueRange(),
                'title' : 'TR("SPY")',
                'columns' : []
            },
            'true-strength-index':
            {
                'code': TrueStrengthIndex(25, 13, 7, MovingAverageType.Exponential),
                'title' : 'TSI("SPY", 25, 13, 7, MovingAverageType.Exponential)',
                'columns' : ['signal']
            },
            'ultimate-oscillator':
            {
                'code': UltimateOscillator(5, 10, 20),
                'title' : 'ULTOSC("SPY", 5, 10, 20)',
                'columns' : []
            },
            'variance':
            {
                'code': Variance(20),
                'title' : 'VAR("SPY", 20)',
                'columns' : []
            },
            'volume-weighted-average-price-indicator':
            {
                'code': VolumeWeightedAveragePriceIndicator(20),
                'title' : 'VWAP("SPY", 20)',
                'columns' : []
            },
            'wilder-accumulative-swing-index':
            {
                'code': WilderAccumulativeSwingIndex(20),
                'title' : 'ASI("SPY", 20)',
                'columns' : []
            },
            'wilder-moving-average':
            {
                'code': WilderMovingAverage(20),
                'title' : 'WWMA("SPY", 20)',
                'columns' : []
            },
            'wilder-swing-index':
            {
                'code': WilderSwingIndex(20),
                'title' : 'SI("SPY", 20)',
                'columns' : []
            },
            'williams-percent-r':
            {
                'code': WilliamsPercentR(20),
                'title' : 'WILR("SPY", 20)',
                'columns' : ['maximum', 'minimum']
            },
        }

        special_indicators = {
            'advance-decline-ratio':
            {
                'code': AdvanceDeclineRatio(""),
                'title' : 'ADR(["SPY", "QQQ"])',
                'columns' : []
            },
            'advance-decline-volume-ratio':
            {
                'code': AdvanceDeclineVolumeRatio(""),
                'title' : 'ADVR(["SPY", "QQQ"])',
                'columns' : []
            },
            'arms-index':
            {
                'code': ArmsIndex(""),
                'title' : 'TRIN(["SPY", "QQQ"])',
                'columns' : []
            },
            'beta':
            {
                'code': Beta("", 20, Symbol.Create("QQQ", SecurityType.Equity, Market.USA), Symbol.Create("SPY", SecurityType.Equity, Market.USA)),
                'title' : 'B("QQQ", "SPY", 20)',
                'columns' : []
            },
            'filtered-identity':
            {
                'code': qb.FilteredIdentity("SPY", filter = lambda x: x.Close > x.Open),
                'title' : 'FilteredIdentity("SPY", filter = lambda x: x.Close > x.Open)',
                'columns' : []
            },
            'intraday-vwap': # 'intraday-vwap'
            {
                'code': IntradayVwap("SPY"),
                'title' : 'VWAP("SPY")',
                'columns' : []
            },
            'target-downside-deviation':
            {
                'code': IndicatorExtensions.Of(TargetDownsideDeviation(50), RateOfChange(1)),
                'title' : 'TDD("SPY", 50)',
                'columns' : []
            },
            'time-profile':
            {
                'code': TimeProfile("", 3, 0.70, 0.05),
                'title' : 'TP("SPY", 3, 0.70, 0.05)',
                'columns' : []
            },
            'volume-profile':
            {
                'code': VolumeProfile("", 3, 0.70, 0.05),
                'title' : 'VP("SPY", 3, 0.70, 0.05)',
                'columns' : []
            }
        }

        for name, indicator in indicators.items():
            code = indicator['code']
            df = qb.Indicator(code, 'SPY', timedelta(365) , Resolution.Daily)
            try:
                generate(name, indicator, df)
            except Exception as e:
                self.Debug(e)

        history = qb.History[TradeBar](["SPY", "QQQ"], timedelta(365), Resolution.Daily)

        roc = RateOfChange(1)
        indicator['code'] = IndicatorExtensions.Of(TargetDownsideDeviation(50), roc)
        indicator['title'] = 'TDD("SPY", 50)'
        index, values = [], []

        for bars in history:
            bar = bars.get("SPY")
            roc.Update(bar.EndTime, bar.Close)
            if indicator['code'].IsReady:
                index.append(bar.EndTime)
                values.append(indicator['code'].Current.Value)

        df = pd.DataFrame(values, index=index, columns=["targetdownsidedeviation"])
        generate("target-downside-deviation", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('advance-decline-ratio')
        indicator['code'] = qb.ADR(["SPY","QQQ"])
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            indicator['code'].Update(bars.get("QQQ"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["advancedeclineratio"])
        generate("advance-decline-ratio", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('advance-decline-volume-ratio')
        indicator['code'] = qb.ADVR(["SPY","QQQ"])
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            indicator['code'].Update(bars.get("QQQ"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["advancedeclinevolumeratio"])
        generate("advance-decline-volume-ratio", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('arms-index')
        indicator['code'] = qb.TRIN(["SPY","QQQ"])
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            indicator['code'].Update(bars.get("QQQ"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["armsindex"])
        generate("arms-index", indicator, df)
        
        index, values = [], []
        indicator = special_indicators.get('intraday-vwap')
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["intradayvwap"])
        generate("intraday-vwap", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('time-profile')
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["timeprofile"])
        generate("time-profile", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('volume-profile')
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["volumeprofile"])
        generate("volume-profile", indicator, df)

        index, values = [], []
        indicator = special_indicators.get('filtered-identity')
        for bars in history:
            indicator['code'].Update(bars.get("SPY"))
            if indicator['code'].IsReady:
                index.append(bars.Time)
                values.append(indicator['code'].Current.Value)
        df = pd.DataFrame(values, index=index, columns=["filteredidentity"])
        generate("filtered-identity", indicator, df)

        self.Quit()