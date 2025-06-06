import plotly.express as px

class IndicatorInfo:

    def __init__(self, code, c_title, py_title, column_groups=[['current']]):
        self.code = code
        self.c_title = c_title
        self.py_title = py_title
        self.column_groups = column_groups


def generate(qb, name, indicator_info, df):
    #display(df)
    # Create figure with subplots - one for each column group
    num_groups = len(indicator_info.column_groups)
    fig, axes = plt.subplots(num_groups, 1, figsize=(10, max(4, 3*num_groups)), sharex=True)
    
    # Ensure axes is a list even for single subplot
    if num_groups == 1:
        axes = [axes]
        
    for idx, columns in enumerate(indicator_info.column_groups):
        if not all([c in df.columns for c in columns]):
            print(f"Unable to plot {name} - The indicator history is missing some columns")
            continue
        df[columns].dropna().plot(ax=axes[idx])
        axes[idx].grid(True)
    fig.suptitle(f"C#: {indicator_info.c_title}\nPy:  {indicator_info.py_title}", x=0.05, ha='left')
    axes[-1].set_xlabel('Date', fontsize=12)
    plt.tight_layout()
    fig.savefig(qb.object_store.get_file_path(f"indicators/images/{name}.png"))
    #plt.show()
    
qb = QuantBook()
qb.add_equity("SPY", Resolution.DAILY)
qb.add_equity("QQQ", Resolution.DAILY)
qb.set_start_date(datetime(2021, 1, 1))
symbol = Symbol.create("SPY", SecurityType.EQUITY, Market.USA)
reference = Symbol.create("QQQ", SecurityType.EQUITY, Market.USA)
option_symbol = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 375, datetime(2021, 1, 8))
option_mirror_symbol = Symbol.create_option("SPY", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 375, datetime(2021, 1, 8))

interest_rate_model = InterestRateProvider()
dividend_yield_model = DividendYieldProvider(symbol)

indicators = {
    'absolute-price-oscillator': IndicatorInfo(
        AbsolutePriceOscillator(10, 20, MovingAverageType.SIMPLE),
        'APO(symbol, 10, 20, MovingAverageType.Simple)',
        'self.apo(symbol, 10, 20, MovingAverageType.SIMPLE)',
        [['fast', 'slow'], ['current', 'histogram', 'signal']] 
    ),
    # https://github.com/QuantConnect/Lean/issues/8791
    'acceleration-bands':  IndicatorInfo(
        AccelerationBands("", 10, 4, MovingAverageType.SIMPLE),
        'ABANDS(symbol, 10, 4, MovingAverageType.Simple)',
        'self.abands(symbol, 10, 4, MovingAverageType.SIMPLE)',
        [['upperband', 'middleband', 'lowerband']]
    ),
    'accumulation-distribution': IndicatorInfo(
        AccumulationDistribution(), 'AD(symbol)', 'self.ad(symbol)'
    ),
    'accumulation-distribution-oscillator': IndicatorInfo(
        AccumulationDistributionOscillator(10, 20), 'ADOSC(symbol, 10, 20)', 'self.adosc(symbol, 10, 20)'
    ),
    'arnaud-legoux-moving-average': IndicatorInfo(
        ArnaudLegouxMovingAverage(10, 6, 0.85), 'ALMA(symbol, 10, 6, 0.85)', 'self.alma(symbol, 10, 6, 0.85)'
    ),
    # https://github.com/QuantConnect/Lean/issues/8791
    'aroon-oscillator': IndicatorInfo(
        AroonOscillator(10, 20), 'AROON(symbol, 10, 20)', 'self.aroon(symbol, 10, 20)'
    ),  
    'augen-price-spike': IndicatorInfo(
        AugenPriceSpike(3), 'APS(symbol, 3)', 'self.aps(symbol, 3)'
    ),
    'auto-regressive-integrated-moving-average': IndicatorInfo(
        AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, True), 'ARIMA(symbol, 1, 1, 1, 20)', 'self.arima(symbol, 1, 1, 20)'
    ),
    'average-directional-index': IndicatorInfo(
        AverageDirectionalIndex(20), 'ADX(symbol, 20)', 'self.adx(symbol, 20)',
        [['current', 'positivedirectionalindex', 'negativedirectionalindex']]
    ),
    'average-directional-movement-index-rating': IndicatorInfo(
        AverageDirectionalMovementIndexRating(20), 'ADXR(symbol, 20)', 'self.adxr(symbol, 20)'
    ),
    'average-range': IndicatorInfo(
        AverageRange(20), 'AR(symbol, 20)', 'self.atr(symbol, 20)'
    ),
    'average-true-range': IndicatorInfo(
        AverageTrueRange(20, MovingAverageType.SIMPLE),
        'ATR(symbol, 20, MovingAverageType.Simple)',
        'self.atr(symbol, 20, MovingAverageType.SIMPLE)'
    ),
    'awesome-oscillator': IndicatorInfo(
        AwesomeOscillator(10, 20, MovingAverageType.SIMPLE),
        'AO(symbol, 10, 20, MovingAverageType.Simple)',
        'self.ao(symbol, 10, 20, MovingAverageType.SIMPLE)',
        [['fastao', 'slowao'], ['current']]
    ),
    'balance-of-power': IndicatorInfo(
        BalanceOfPower(), 'BOP(symbol)', 'self.bop(symbol)'
    ),
    'bollinger-bands': IndicatorInfo(
        BollingerBands(30, 2), 'BB(symbol, 30, 2)', 'self.bb(symbol, 30, 2)',
        [['price', 'upperband', 'middleband', 'lowerband'], ['percentb'], ['standarddeviation']]
    ),
    'chaikin-money-flow': IndicatorInfo(
        ChaikinMoneyFlow("SPY", 20), 'CMF(symbol, 20)', 'self.cmf(symbol, 20)'
    ),
    'chande-kroll-stop': IndicatorInfo(
        ChandeKrollStop(10, 1, 9), 'CKS(symbol, 10, 1, 9)', 'self.cks(symbol, 10, 1, 9)',
        [['shortstop', 'current', 'longstop']]
    ),
    'chande-momentum-oscillator': IndicatorInfo(
        ChandeMomentumOscillator(20), 'CMO(symbol, 20)', 'self.cmo(symbol, 20)'
    ),
    'choppiness-index': IndicatorInfo(
        ChoppinessIndex(14), 'CHOP(symbol, 14)', 'self.chop(symbol, 14)'
    ),
    'commodity-channel-index': IndicatorInfo(
        CommodityChannelIndex(20, MovingAverageType.SIMPLE),
        'CCI(symbol, 20, MovingAverageType.Simple)',
        'self.cci(symbol, 20, MovingAverageType.SIMPLE)',
        [['current'], ['typicalpriceaverage'], ['typicalpricemeandeviation']]
    ),
    'connors-relative-strength-index': IndicatorInfo(
        ConnorsRelativeStrengthIndex(3, 2, 100), 'CRSI(symbol, 3, 2, 100)', 'self.crsi(symbol, 3, 2, 100)'
    ),
    'coppock-curve': IndicatorInfo(
        CoppockCurve(11, 14, 10), 'CC(symbol, 11, 14, 10)', 'self.cc(symbol, 11, 14, 10)'
    ),
    'de-marker-indicator': IndicatorInfo(
        DeMarkerIndicator(20, MovingAverageType.SIMPLE),
        'DEM(symbol, 20, MovingAverageType.Simple)',
        'self.dem(symbol, 20, MovingAverageType.SIMPLE)'
    ),
    'derivative-oscillator': IndicatorInfo(
        DerivativeOscillator("DerivativeOscillator", 14, 5, 3, 9), 'DO(symbol, 14, 5, 3, 9)', 'self.do(symbol, 14, 5, 3, 9)'
    ),
    'detrended-price-oscillator': IndicatorInfo(
        DetrendedPriceOscillator(20), 'DPO(symbol, 20)', 'self.dpo(symbol, 20)'
    ),
    'donchian-channel': IndicatorInfo(
        DonchianChannel(20, 20), 'DCH(symbol, 20, 20)', 'self.dch(symbol, 20, 20)',
        [['upperband', 'lowerband']]
    ),
    'double-exponential-moving-average': IndicatorInfo(
        DoubleExponentialMovingAverage(20), 'DEMA(symbol, 20)', 'self.dema(symbol, 20)'
    ),
    'ease-of-movement-value': IndicatorInfo(
        EaseOfMovementValue(1, 10000), 'EMV(symbol, 1, 10000)', 'self.emv(symbol, 1, 10000)'
    ),
    'exponential-moving-average': IndicatorInfo(
        ExponentialMovingAverage(20, 0.5), 'EMA(symbol, 20, 0.5)', 'self.ema(symbol, 20, 0.5)'
    ),
    'fisher-transform': IndicatorInfo(
        FisherTransform(20), 'FISH(symbol, 20)', 'self.fish(symbol, 20)'
    ),
    'force-index': IndicatorInfo(
        ForceIndex(13), 'FI(symbol, 13)', 'self.fi(symbol, 13)'
    ),
    'fractal-adaptive-moving-average': IndicatorInfo(
        FractalAdaptiveMovingAverage(20, 198), 'FRAMA(symbol, 20, 198)', 'self.frama(symbol, 20, 198)'
    ),
    'heikin-ashi': IndicatorInfo(
        HeikinAshi(), 'HeikinAshi(symbol)', 'self.heikin_ashi(symbol)', 
        [['open', 'high', 'low', 'close'], ['volume']]
    ),
    'hilbert-transform': IndicatorInfo(
        HilbertTransform(7, 0.635, 0.338), 'HT(symbol, 7, 0.635, 0.338)', 'self.ht(symbol, 7, 0.635, 0.338)',
        [['inphase', 'quadrature']]
    ),
    'hull-moving-average': IndicatorInfo(
        HullMovingAverage(20), 'HMA(symbol, 20)', 'self.hma(symbol, 20)'
    ),
    'hurst-exponent': IndicatorInfo(
        HurstExponent(32), 'HE(symbol, 32)', 'self.he(symbol, 32)'
    ),
    'ichimoku-kinko-hyo': IndicatorInfo(
        IchimokuKinkoHyo(9, 26, 17, 52, 26, 26), 
        'ICHIMOKU(symbol, 9, 26, 17, 52, 26, 26)', 'self.ichimoku(symbol, 9, 26, 17, 52, 26, 26)',
        [['chikou', 'kijun', 'senkoua', 'senkoub', 'tenkan']]
    ),
    'identity': IndicatorInfo(
        Identity("SPY"), 'Identity(symbol)', 'self.identity(symbol)'
    ),
    'internal-bar-strength': IndicatorInfo(
        InternalBarStrength(), 'IBS(symbol)', 'self.ibs(symbol)'
    ),
    'kaufman-adaptive-moving-average': IndicatorInfo(
        KaufmanAdaptiveMovingAverage(20, 10, 20), 'KAMA(symbol, 20, 10, 20)', 'self.kama(symbol, 20, 10, 20)'
    ),
    'kaufman-efficiency-ratio': IndicatorInfo(
        KaufmanEfficiencyRatio(20), 'KER(symbol, 20)', 'self.ker(symbol, 20)'
    ),
    'keltner-channels': IndicatorInfo(
        KeltnerChannels(20, 2, MovingAverageType.SIMPLE), 
        'KCH(symbol, 20, 2, MovingAverageType.Simple)', 
        'self.kch(symbol, 20, 2, MovingAverageType.SIMPLE)',
        [['upperband', 'middleband', 'lowerband'], ['averagetruerange']]
    ),
    'least-squares-moving-average': IndicatorInfo(
        LeastSquaresMovingAverage(20), 'LSMA(symbol, 20)', 'self.lsma(symbol, 20)',
        [['current', 'intercept'], ['slope']]
    ),
    'linear-weighted-moving-average': IndicatorInfo(
        LinearWeightedMovingAverage(20), 'LWMA(symbol, 20)', 'self.lwma(symbol, 20)'
    ),
    'log-return': IndicatorInfo(
        LogReturn(20), 'LOGR(symbol, 20)', 'self.logr(symbol, 20)'
    ),
    'mass-index': IndicatorInfo(
        MassIndex(9, 25), 'MASS(symbol, 9, 25)', 'self.mass(symbol, 9, 25)'
    ),
    'maximum': IndicatorInfo(
        Maximum(20), 'MAX(symbol, 20)', 'self.max(symbol, 20)'
    ),
    'mc-ginley-dynamic': IndicatorInfo(
        McGinleyDynamic(10), 'MGD(symbol, 10)', 'self.mgd(symbol, 10)'
    ),
    'mid-point': IndicatorInfo(
        MidPoint(20), 'MIDPOINT(symbol, 20)', 'self.midpoint(symbol, 20)'
    ),
    'mid-price': IndicatorInfo(
        MidPrice(20), 'MIDPRICE(symbol, 20)', 'self.midprice(symbol, 20)'
    ),
    'mean-absolute-deviation': IndicatorInfo(
        MeanAbsoluteDeviation(20), 'MAD(symbol, 20)', 'self.mad(symbol, 20)',
        [['mean'], ['current']]
    ),
    'mesa-adaptive-moving-average': IndicatorInfo(
        MesaAdaptiveMovingAverage(0.5, 0.05), 'MAMA(symbol, 0.5, 0.05)', 'self.mama(symbol, 0.5, 0.05)',
        [['current', 'fama']]
    ),
    'minimum': IndicatorInfo(
        Minimum(20), 'MIN(symbol, 20)', 'self.min(symbol, 20)'
    ),
    'momentum': IndicatorInfo(
        Momentum(20), 'MOM(symbol, 20)', 'self.mom(symbol, 20)'
    ),
    'momentum-percent': IndicatorInfo(
        MomentumPercent(20), 'MOMP(symbol, 20)', 'self.momp(symbol, 20)'
    ),
    'momersion': IndicatorInfo(
        MomersionIndicator(10, 20), 'MOMERSION(symbol, 10, 20)', 'self.momersion(symbol, 10, 20)'
    ),
    'money-flow-index': IndicatorInfo(     # See https://github.com/QuantConnect/Lean/issues/8791
        MoneyFlowIndex(20), 'MFI(symbol, 20)', 'self.mfi(symbol, 20)',
        [['current'], ['positivemoneyflow', 'negativemoneyflow']]
    ),
    'moving-average-convergence-divergence': IndicatorInfo(
        MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.EXPONENTIAL), 
        'MACD(symbol, 12, 26, 9, MovingAverageType.Exponential)', 
        'self.macd(symbol, 12, 26, 9, MovingAverageType.EXPONENTIAL)',
        [['fast', 'slow'], ['current', 'signal', 'histogram']]
    ),
    'normalized-average-true-range': IndicatorInfo(
        NormalizedAverageTrueRange(20), 'NATR(symbol, 20)', 'self.natr(symbol, 20)'
    ),
    'on-balance-volume': IndicatorInfo(
        OnBalanceVolume(), 'OBV(symbol)', 'self.obv(symbol)'
    ),
    'parabolic-stop-and-reverse': IndicatorInfo(
        ParabolicStopAndReverse(0.02, 0.02, 0.2), 'PSAR(symbol, 0.02, 0.02, 0.2)', 'self.psar(symbol, 0.02, 0.02, 0.2)'
    ),
    'percentage-price-oscillator': IndicatorInfo(
        PercentagePriceOscillator(10, 20, MovingAverageType.SIMPLE), 
        'PPO(symbol, 10, 20, MovingAverageType.Simple)', 
        'self.ppo(symbol, 10, 20, MovingAverageType.SIMPLE)',
        [['fast', 'slow'], ['current', 'signal', 'histogram']]
    ),
    'pivot-points-high-low': IndicatorInfo(
        PivotPointsHighLow(10, 10, 100), 'PPHL(symbol, 10, 10, 100)', 'self.pphl(symbol, 10, 10, 100)'
    ),
    'premier-stochastic-oscillator': IndicatorInfo(
        PremierStochasticOscillator(14, 3), 'PSO(symbol, 14, 3)', 'self.pso(symbol, 14, 3)'
    ),
    'rate-of-change': IndicatorInfo(
        RateOfChange(10), 'ROC(symbol, 10)', 'self.roc(symbol, 10)'
    ),
    'rate-of-change-percent': IndicatorInfo(
        RateOfChangePercent(10), 'ROCP(symbol, 10)', 'self.rocp(symbol, 10)'
    ),
    'rate-of-change-ratio': IndicatorInfo(
        RateOfChangeRatio(10), 'ROCR(symbol, 10)', 'self.rocr(symbol, 10)'
    ),
    'regression-channel': IndicatorInfo(
        RegressionChannel(20, 2), 'RC(symbol, 20, 2)', 'self.rc(symbol, 20, 2)',
        [['upperchannel', 'current', 'lowerchannel', 'intercept'], ['slope']]
    ),
    'relative-daily-volume': IndicatorInfo(
        RelativeDailyVolume(2), 'RDV(symbol, 2)', 'self.rdv(symbol, 2)'
    ),
    # See https://github.com/QuantConnect/Lean/issues/8794
    'relative-moving-average':  IndicatorInfo(  
        RelativeMovingAverage(20), 'RMA(symbol, 20)', 'self.rma(symbol, 20)'
    ), 
    'relative-strength-index': IndicatorInfo(
        RelativeStrengthIndex(14), 'RSI(symbol, 14)', 'self.rsi(symbol, 14)',
        [['current'], ['averagegain', 'averageloss']]
    ),
    'relative-vigor-index': IndicatorInfo(
        RelativeVigorIndex(20, MovingAverageType.SIMPLE), 'RVI(symbol, 20, MovingAverageType.Simple)', 'self.rvi(symbol, 20, MovingAverageType.SIMPLE)'
    ),
    'rogers-satchell-volatility': IndicatorInfo(
        RogersSatchellVolatility(30), 'RSV(symbol, 30)', 'self.rsv(symbol, 30)'
    ),
    'schaff-trend-cycle': IndicatorInfo(
        SchaffTrendCycle(5, 10, 20, MovingAverageType.EXPONENTIAL), 
        'STC(symbol, 5, 10, 20, MovingAverageType.Exponential)', 
        'self.stc(symbol, 5, 10, 20, MovingAverageType.EXPONENTIAL)'
    ),
    'sharpe-ratio': IndicatorInfo(
        SharpeRatio(22, 0.03), 'SR(symbol, 22, 0.03)', 'self.sr(symbol, 22, 0.03)'
    ),
    'simple-moving-average': IndicatorInfo(
        SimpleMovingAverage(20), 'SMA(symbol, 20)', 'self.sma(symbol, 20)'
    ),
    'smoothed-on-balance-volume': IndicatorInfo(
        SmoothedOnBalanceVolume(20), 'SOBV(symbol, 20)', 'self.sobv(symbol, 20)'
    ),
    'sortino-ratio': IndicatorInfo(
        SortinoRatio(22), 'SORTINO(symbol, 22)', 'self.sortino(symbol, 22)'
    ),
    'squeeze-momentum': IndicatorInfo(
        SqueezeMomentum("SM", 20, 2, 20, 1.5), 'SM(symbol, 20, 2, 20, 1.5)', 'self.sm(symbol, 20, 2, 20, 1.5)'
    ),
    'standard-deviation': IndicatorInfo(
        StandardDeviation(22), 'STD(symbol, 22)', 'self.std(symbol, 22)'
    ),
    'stochastic': IndicatorInfo(
        Stochastic(20, 10, 20), 'STO(symbol, 20, 10, 20)', 'self.sto(symbol, 20, 10, 20)',
        [['faststoch', 'stochd', 'stochk']]
    ),
    'stochastic-relative-strength-index': IndicatorInfo(
        StochasticRelativeStrengthIndex(14, 14, 3, 3), 'SRSI(symbol, 14, 14, 3, 3)', 'self.srsi(symbol, 14, 14, 3, 3)',
        [['current'], ["k", "d"]]
    ),
    'sum': IndicatorInfo(
        Sum(20), 'SUM(symbol, 20)', 'self.sum(symbol, 20)'
    ),
    'super-trend': IndicatorInfo(
        SuperTrend(20, 2, MovingAverageType.WILDERS), 
        'STR(symbol, 20, 2, MovingAverageType.Wilders)', 
        'self.str(symbol, 20, 2, MovingAverageType.WILDERS)'
    ),
    'swiss-army-knife': IndicatorInfo(
        SwissArmyKnife(20, 0.2, SwissArmyKnifeTool.GAUSS), 
        'SWISS(symbol, 20, 0.2, SwissArmyKnifeTool.Gauss)', 
        'self.swiss(symbol, 20, 0.2, SwissArmyKnifeTool.GAUSS)'
    ),
    't3-moving-average': IndicatorInfo(
        T3MovingAverage(30, 0.7), 'T3(symbol, 30, 0.7)', 'self.t3(symbol, 30, 0.7)'
    ),
    'time-series-forecast': IndicatorInfo(
        TimeSeriesForecast(3), 'TSF(symbol, 3)', 'self.tsf(symbol, 3)'
    ),
    'triangular-moving-average': IndicatorInfo(
        TriangularMovingAverage(20), 'TRIMA(symbol, 20)', 'self.trima(symbol, 20)'
    ),
    'triple-exponential-moving-average': IndicatorInfo(
        TripleExponentialMovingAverage(20), 'TEMA(symbol, 20)', 'self.tema(symbol, 20)'
    ),
    'trix': IndicatorInfo(
        Trix(20), 'TRIX(symbol, 20)', 'self.trix(symbol, 20)'
    ),
    'true-range': IndicatorInfo(
        TrueRange(), 'TR(symbol)', 'self.tr(symbol)'
    ),
    'true-strength-index': IndicatorInfo(
        TrueStrengthIndex(25, 13, 7, MovingAverageType.EXPONENTIAL), 
        'TSI(symbol, 25, 13, 7, MovingAverageType.Exponential)', 
        'self.tsi(symbol, 25, 13, 7, MovingAverageType.EXPONENTIAL)',
        [['current', 'signal']]
    ),
    'ultimate-oscillator': IndicatorInfo(
        UltimateOscillator(5, 10, 20), 'ULTOSC(symbol, 5, 10, 20)', 'self.ultosc(symbol, 5, 10, 20)'
    ),
    'value-at-risk': IndicatorInfo(
        ValueAtRisk(252, 0.95), 'VAR(symbol, 252, 0.95)', 'self.var(symbol, 252, 0.95)'
    ),
    'variable-index-dynamic-average': IndicatorInfo(
        VariableIndexDynamicAverage(20), 'VIDYA(symbol, 20)', 'self.vidya(symbol, 20)'
    ),
    'variance': IndicatorInfo(
        Variance(20), 'VAR(symbol, 20)', 'self.var(symbol, 20)'
    ),
    'volume-weighted-average-price-indicator': IndicatorInfo(
        VolumeWeightedAveragePriceIndicator(20), 'VWAP(symbol, 20)', 'self.vwap(symbol, 20)'
    ),
    'volume-weighted-moving-average': IndicatorInfo(
        VolumeWeightedMovingAverage(20), 'VWMA(symbol, 20)', 'self.vwma(symbol, 20)'
    ),
    'vortex': IndicatorInfo(
        Vortex(14), 'VTX(symbol, 14)', 'self.vtx(symbol, 14)',
        [['current', "plusvortex", "minusvortex"]]
    ),
    'wilder-accumulative-swing-index': IndicatorInfo(
        WilderAccumulativeSwingIndex(20), 'ASI(symbol, 20)', 'self.asi(symbol, 20)'
    ),
    'wilder-moving-average': IndicatorInfo(
        WilderMovingAverage(20), 'WWMA(symbol, 20)', 'self.wwma(symbol, 20)'
    ),
    'wilder-swing-index': IndicatorInfo(
        WilderMovingAverage(20), 'SI(symbol, 20)', 'self.si(symbol, 20)'
    ),
    'williams-percent-r': IndicatorInfo(
        WilliamsPercentR(20), 'WILR(symbol, 20)', 'self.wilr(symbol, 20)'
    ),
    'zero-lag-exponential-moving-average': IndicatorInfo(
        ZeroLagExponentialMovingAverage(10), 'ZLEMA(symbol, 10)', 'self.zlema(symbol, 10)'
    ),
}

special_indicators = {
    'advance-decline-difference': IndicatorInfo(
        AdvanceDeclineDifference(""), 'ADDIFF([symbol, reference])', 'self.addiff([symbol, reference])'
    ),
    'advance-decline-ratio': IndicatorInfo(
        AdvanceDeclineRatio(""), 'ADR([symbol, reference])', 'self.adr([symbol, reference])'
    ),
    'advance-decline-volume-ratio': IndicatorInfo(
        AdvanceDeclineVolumeRatio(""), 'ADVR([symbol, reference])', 'self.advr([symbol, reference])'
    ),
    'arms-index': IndicatorInfo(
        ArmsIndex(""), 'TRIN([symbol, reference])', 'self.tring([symbol, reference])'
    ),
    'alpha': IndicatorInfo(
        Alpha("", symbol, reference, 20), 'A(symbol, reference, 20)', 'self.a(symbol, reference, 20)'
    ),
    'beta': IndicatorInfo(
        Beta("", symbol, reference, 20), 'B(symbol, reference, 20)', 'self.b(symbol, reference, 20)'
    ),
    'correlation': IndicatorInfo(
        Correlation("", symbol, reference, 20, CorrelationType.PEARSON), 
        'C(symbol, reference, 20, CorrelationType.Pearson)', 'self.c(symbol, reference, 20, CorrelationType.PEARSON)'
    ),
    'filtered-identity': IndicatorInfo(
        qb.FilteredIdentity("SPY", filter=lambda x: x.close > x.open), 
        'FilteredIdentity(symbol, filter: x => x.close > x.open)', 
        'self.filtered_identity(symbol, filter=lambda x: x.close > x.open)'
    ),
    'intraday-vwap': IndicatorInfo(
        IntradayVwap("SPY"), 'VWAP(symbol)', 'self.vwap(symbol)'
    ),
    'mc-clellan-oscillator': IndicatorInfo(
        McClellanOscillator(""), 'MOSC([symbol, reference])', 'self.mosc([symbol, reference])'
    ),
    'mc-clellan-summation-index': IndicatorInfo(
        McClellanSummationIndex(""), 'MSI([symbol, reference])', 'self.msi([symbol, reference])'
    ),
    # See https://github.com/QuantConnect/Lean/issues/8808
    'target-downside-deviation': IndicatorInfo(
        IndicatorExtensions.of(TargetDownsideDeviation(50), RateOfChange(1)), 'TDD(symbol, 50)', 'self.tdd(symbol, 50)'
    ),
    'time-profile': IndicatorInfo(
        TimeProfile("", 3, 0.70, 0.05), 'TP(symbol, 3, 0.70, 0.05)', 'self.tp(symbol, 3, 0.70, 0.05)'
    ),
    'volume-profile': IndicatorInfo(
        VolumeProfile("", 3, 0.70, 0.05), 'VP(symbol, 3, 0.70, 0.05)', 'self.vp(symbol, 3, 0.70, 0.05)'
    ),
    'zig-zag': IndicatorInfo(
        ZigZag(0.05, 1), 'ZZ(symbol, 0.05, 1)', 'self.zz(symbol, 0.05, 1)',
        [["zigzag", "highpivot", "lowpivot"]]
    ),
}

option_indicators = {
    'implied-volatility': IndicatorInfo(
        ImpliedVolatility(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol),
        'IV(optionSymbol, optionMirrorSymbol)', 'self.iv(option_symbol, option_mirror_symbol)'
    ),
    'delta': IndicatorInfo(
        Delta(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol),
        'D(optionSymbol, optionMirrorSymbol)', 'self.d(option_symbol, option_mirror_symbol)'
    ),
    'gamma': IndicatorInfo(
        Gamma(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol), 
        'G(optionSymbol, optionMirrorSymbol)', 'self.g(option_symbol, option_mirror_symbol)'
    ),
    'vega': IndicatorInfo(
        Vega(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol),
        'V(optionSymbol, optionMirrorSymbol)', 'self.v(option_symbol, option_mirror_symbol)'
    ),
    'theta': IndicatorInfo(
        Theta(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol), 
        'T(optionSymbol, optionMirrorSymbol)', 'self.t(option_symbol, option_mirror_symbol)'
    ),
    'rho': IndicatorInfo(
        Rho(option_symbol, interest_rate_model, dividend_yield_model, option_mirror_symbol), 
        'R(optionSymbol, optionMirrorSymbol)', 'self.r(option_symbol, option_mirror_symbol)'
    )
}

for name, indicator_info in indicators.items():
    generate(qb, name, indicator_info, qb.indicator(indicator_info.code, 'SPY', timedelta(365) , Resolution.DAILY))

# Get data for plotting "special" indicators and Option indicators.
history = qb.history[TradeBar](["SPY", "QQQ"], timedelta(365), Resolution.DAILY)
option_history = qb.history[QuoteBar]([option_symbol, option_mirror_symbol], timedelta(365), Resolution.DAILY)

# Plot TDD indicator.   # See https://github.com/QuantConnect/Lean/issues/8808
roc = RateOfChange(1) 
indicator_info = IndicatorInfo(
    IndicatorExtensions.of(TargetDownsideDeviation(50), roc), 'TDD(symbol, 50)', 'self.tdd(symbol, 50)'
)
index, values = [], []
for bars in history:
    bar = bars.get("SPY")
    roc.update(bar.end_time, bar.close)
    if indicator_info.code.is_ready:
        index.append(bar.end_time)
        values.append(indicator_info.code.current.value)
generate(qb, "target-downside-deviation", indicator_info, pd.DataFrame(values, index=index, columns=["current"]))

def generate_special_indicator(name, code, tickers, history):
    index, values = [], []
    indicator_info = special_indicators.get(name)
    if code:
        indicator_info.code = code
    for bars in history:
        for ticker in tickers:
            indicator_info.code.update(bars.get(ticker))
        if indicator_info.code.is_ready:
            index.append(bars.time)
            values.append(indicator_info.code.current.value)
    generate(qb, name, indicator_info, pd.DataFrame(values, index=index, columns=['current']))

# Plot "special" indicators
tickers = ['SPY', 'QQQ']
generate_special_indicator('advance-decline-difference', qb.addiff(tickers), tickers, history)
generate_special_indicator('advance-decline-ratio', qb.adr(tickers), tickers, history)
generate_special_indicator('advance-decline-volume-ratio', qb.advr(tickers), tickers, history)
generate_special_indicator('alpha', qb.a(*tickers, 1, 20), tickers, history)
generate_special_indicator('beta', qb.b(*tickers, 20), tickers, history)
generate_special_indicator('correlation', qb.c(*tickers, 20, CorrelationType.PEARSON), tickers, history)
generate_special_indicator('arms-index', qb.trin(tickers), tickers, history)
generate_special_indicator('mc-clellan-oscillator', qb.mosc(tickers), tickers, history)
generate_special_indicator('mc-clellan-summation-index', qb.msi(tickers), tickers, history)
generate_special_indicator('time-profile', None, ['SPY'], history)
generate_special_indicator('volume-profile', None, ['SPY'], history)
generate_special_indicator('filtered-identity', None, ['SPY'], history)
generate_special_indicator('intraday-vwap', None, ['SPY'], qb.history[TradeBar]([symbol], datetime(2020, 5, 1), datetime(2020, 5, 2), Resolution.MINUTE))


# Plot the zig-zag indicator
index, values = [], []
indicator_info = special_indicators.get("zig-zag")
indicator_info.code = qb.ZZ("SPY", 0.05, 1)
for bars in history:
    indicator_info.code.update(bars.get("SPY"))
    if indicator_info.code.is_ready:
        index.append(bars.time)
        values.append([
            indicator_info.code.current.value, 
            indicator_info.code.high_pivot.current.value,
            indicator_info.code.low_pivot.current.value]
        )
generate(qb, "zig-zag", indicator_info, pd.DataFrame(values, index=index, columns=["zigzag", "highpivot", "lowpivot"]))

# Plot Option indicators.
times = set(bars.get("SPY").end_time for bars in history).intersection(bars.get(option_symbol).end_time for bars in option_history)
history = [bar for bar in history if bar.get("SPY").end_time in times]
option_history = [bar for bar in option_history if bar.get(option_symbol).end_time in times]
for name, indicator_info in option_indicators.items():
    index, values = [], []
    for bars, quotebars in zip(history, option_history):
        indicator_info.code.update(IndicatorDataPoint(symbol, bars.get("SPY").end_time, bars.get("SPY").close))
        indicator_info.code.update(IndicatorDataPoint(option_symbol, quotebars.get(option_symbol).end_time, quotebars.get(option_symbol).close))
        indicator_info.code.update(IndicatorDataPoint(option_mirror_symbol, quotebars.get(option_mirror_symbol).end_time, quotebars.get(option_mirror_symbol).close))
        if indicator_info.code.is_ready:
            index.append(bars.get("SPY").end_time)
            values.append(indicator_info.code.current.value)
    generate(qb, name, indicator_info, pd.DataFrame(values, index=index, columns=['current']))