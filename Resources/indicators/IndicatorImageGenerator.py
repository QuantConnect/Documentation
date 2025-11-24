# region imports
from AlgorithmImports import *
import matplotlib.pyplot as plt
from matplotlib.dates import MonthLocator
# endregion
''' View Images in Notebook using:
from IPython.display import Image
store = QuantBook().object_store
name = 'delta'
Image(filename=store.get_file_path(f"indicators/images/{name}.png")) 
'''
class IndicatorInfo:
    def __init__(self, code, c_code, c_title, py_title, column_groups=[['current']]):
        self.code = code
        self.c_title = c_title
        self.py_title = py_title
        self.column_groups = column_groups

    def generate(self, df, fname) -> str:
        if df.empty:
            return 'Empty Dataframe'
        # Create figure with subplots - one for each column group
        num_groups = len(self.column_groups)
        fig, axes = plt.subplots(num_groups, 1, figsize=(10, max(4, 3*num_groups)), sharex=True)
        # Ensure axes is a list even for single subplot
        if num_groups == 1:
            axes = [axes]
        output = ''
        for idx, columns in enumerate(self.column_groups):
            if not all([c in df.columns for c in columns]):
                output += f"Unable to plot {fname} - The indicator history is missing some columns\n"
                continue
            df[columns].dropna().plot(ax=axes[idx])
            axes[idx].grid(True)
        fig.suptitle(f"C#: {self.c_title}\nPy:  {self.py_title}", x=0.05, ha='left')
        axes[-1].set_xlabel('Date', fontsize=12)
        axes[-1].xaxis.set_major_locator(MonthLocator())
        plt.tight_layout()
        fig.savefig(fname)
        #plt.show()
        return output

class IndicatorImageGenerator(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2024, 12, 31)
        self.set_end_date(self.start_date)
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._option = SymbolRepresentation.parse_option_ticker_osi("SPY 241231C00585000")
        self._mirror_option = SymbolRepresentation.parse_option_ticker_osi("SPY 241231P00585000")
        interest_rate_model = InterestRateProvider()
        dividend_yield_model = DividendYieldProvider(self._symbol)

        indicators = self.get_indicators()
        name = 'intraday-vwap'; info = indicators.pop(name)
        history = self.indicator_history(info.code, self._symbol, timedelta(2), Resolution.MINUTE)
        error = info.generate(history.data_frame, self.object_store.get_file_path(f"indicators/images/{name}.png"))
        self.debug(f'Processed: {name}' + (f' :: {error=}' if error else ''))

        for name, info in indicators.items():
            history = self.indicator_history(info.code, self._symbol, timedelta(365), Resolution.DAILY)
            error = info.generate(history.data_frame, self.object_store.get_file_path(f"indicators/images/{name}.png"))
            self.debug(f'Processed: {name}' + (f' :: {error=}' if error else ''))

        for name, info in self.get_option_indicators(interest_rate_model, dividend_yield_model).items():
            history = self.indicator_history(info.code, [self._symbol, self._option, self._mirror_option], timedelta(365), Resolution.DAILY)
            error = info.generate(history.data_frame, self.object_store.get_file_path(f"indicators/images/{name}.png"))
            self.debug(f'Processed: {name}' + (f' :: {error=}' if error else ''))

        # QQQ is the Symbol
        self._reference = self._symbol
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        for name, info in self.get_special_indicators().items():
            if hasattr(info.code, 'add'):
                [info.code.add(symbol) for symbol in [self._symbol, self._reference]]
            history = self.indicator_history(info.code, [self._symbol, self._reference], timedelta(365), Resolution.DAILY)
            error = info.generate(history.data_frame, self.object_store.get_file_path(f"indicators/images/{name}.png"))
            self.debug(f'Processed: {name}' + (f' :: {error=}' if error else ''))

    def get_indicators(self) -> dict[str,IndicatorInfo]:
        return {
        'intraday-vwap': IndicatorInfo(
            IntradayVwap(), 
            'IntradayVwap()', 
            'VWAP(_symbol)', 
            'self.vwap(self._symbol)'
        ),
        'absolute-price-oscillator': IndicatorInfo(
            AbsolutePriceOscillator(10, 20, MovingAverageType.SIMPLE),
            'AbsolutePriceOscillator(10, 20, MovingAverageType.Simple)',
            'APO(_symbol, 10, 20, MovingAverageType.Simple)',
            'self.apo(self._symbol, 10, 20, MovingAverageType.SIMPLE)',
            [['fast', 'slow'], ['current', 'histogram', 'signal']] 
        ),
        'acceleration-bands':  IndicatorInfo(
            AccelerationBands(10, 4, MovingAverageType.SIMPLE),
            'AccelerationBands(10, 4m, MovingAverageType.Simple)',
            'ABANDS(_symbol, 10, 4m, MovingAverageType.Simple)',
            'self.abands(self._symbol, 10, 4, MovingAverageType.SIMPLE)',
            [['upperband', 'middleband', 'lowerband']]
        ),
        'accumulation-distribution': IndicatorInfo(
            AccumulationDistribution(), 
            'AccumulationDistribution()', 
            'AD(_symbol)',
            'self.ad(self._symbol)'
        ),
        'accumulation-distribution-oscillator': IndicatorInfo(
            AccumulationDistributionOscillator(10, 20), 
            'AccumulationDistributionOscillator(10, 20)', 
            'ADOSC(_symbol, 10, 20)',
            'self.adosc(self._symbol, 10, 20)'
        ),
        'arnaud-legoux-moving-average': IndicatorInfo(
            ArnaudLegouxMovingAverage(10, 6, 0.85), 
            'ArnaudLegouxMovingAverage(10, 6, 0.85m)', 
            'ALMA(_symbol, 10, 6, 0.85m)', 
            'self.alma(self._symbol, 10, 6, 0.85)'
        ),
        'aroon-oscillator': IndicatorInfo(
            AroonOscillator(10, 20), 
            'AroonOscillator(10, 20)', 
            'AROON(_symbol, 10, 20)',
            'self.aroon(self._symbol, 10, 20)'
        ),  
        'augen-price-spike': IndicatorInfo(
            AugenPriceSpike(3), 
            'AugenPriceSpike(3)', 
            'APS(_symbol, 3)', 
            'self.aps(self._symbol, 3)'
        ),
        'auto-regressive-integrated-moving-average': IndicatorInfo(
            AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, True), 
            'AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, true)', 
            'ARIMA(_symbol, 1, 1, 1, 20)', 
            'self.arima(self._symbol, 1, 1, 1, 20)'
        ),
        'average-directional-index': IndicatorInfo(
            AverageDirectionalIndex(20), 
            'AverageDirectionalIndex(20)',
            'ADX(_symbol, 20)', 
            'self.adx(self._symbol, 20)',
            [['current', 'positivedirectionalindex', 'negativedirectionalindex']]
        ),
        'average-directional-movement-index-rating': IndicatorInfo(
            AverageDirectionalMovementIndexRating(20), 
            'AverageDirectionalMovementIndexRating(20)', 
            'ADXR(_symbol, 20)', 
            'self.adxr(self._symbol, 20)'
        ),
        'average-range': IndicatorInfo(
            AverageRange(20),
            'AverageRange(20)',
            'AR(_symbol, 20)',
            'self.ar(self._symbol, 20)'
        ),
        'average-true-range': IndicatorInfo(
            AverageTrueRange(20, MovingAverageType.SIMPLE),
            'AverageTrueRange(20, MovingAverageType.Simple)',
            'ATR(_symbol, 20, MovingAverageType.Simple)',
            'self.atr(self._symbol, 20, MovingAverageType.SIMPLE)'
        ),
        'awesome-oscillator': IndicatorInfo(
            AwesomeOscillator(10, 20, MovingAverageType.SIMPLE),
            'AwesomeOscillator(10, 20, MovingAverageType.Simple)',
            'AO(_symbol, 10, 20, MovingAverageType.Simple)',
            'self.ao(self._symbol, 10, 20, MovingAverageType.SIMPLE)',
            [['fastao', 'slowao'], ['current']]
        ),
        'balance-of-power': IndicatorInfo(
            BalanceOfPower(), 
            'BalanceOfPower()', 
            'BOP(_symbol)', 
            'self.bop(self._symbol)'
        ),
        'bollinger-bands': IndicatorInfo(
            BollingerBands(30, 2), 
            'BollingerBands(30, 2m)', 
            'BB(_symbol, 30, 2m)', 
            'self.bb(self._symbol, 30, 2)',
            [['price', 'upperband', 'middleband', 'lowerband'], ['percentb'], ['standarddeviation']]
        ),
        'chaikin-money-flow': IndicatorInfo(
            ChaikinMoneyFlow(20), 
            'ChaikinMoneyFlow(20)', 
            'CMF(_symbol, 20)', 
            'self.cmf(self._symbol, 20)'
        ),
        'chaikin-oscillator': IndicatorInfo(
            ChaikinOscillator(3,10), 
            'ChaikinOscillator(3, 10)', 
            'CO(_symbol, 3, 10)', 
            'self.co(self._symbol, 3, 10)'
        ),
        'chande-kroll-stop': IndicatorInfo(
            ChandeKrollStop(10, 1, 9), 
            'ChandeKrollStop(10, 1, 9)', 
            'CKS(_symbol, 10, 1, 9)', 
            'self.cks(self._symbol, 10, 1, 9)',
            [['shortstop', 'current', 'longstop']]
        ),
        'chande-momentum-oscillator': IndicatorInfo(
            ChandeMomentumOscillator(20), 
            'ChandeMomentumOscillator(20)', 
            'CMO(_symbol, 20)', 
            'self.cmo(self._symbol, 20)'
        ),
        'choppiness-index': IndicatorInfo(
            ChoppinessIndex(14),
            'ChoppinessIndex(14)',
            'CHOP(_symbol, 14)',
            'self.chop(self._symbol, 14)'
        ),
        'commodity-channel-index': IndicatorInfo(
            CommodityChannelIndex(20, MovingAverageType.SIMPLE),
            'CommodityChannelIndex(20, MovingAverageType.Simple)',
            'CCI(_symbol, 20, MovingAverageType.Simple)',
            'self.cci(self._symbol, 20, MovingAverageType.SIMPLE)',
            [['current'], ['typicalpriceaverage'], ['typicalpricemeandeviation']]
        ),
        'connors-relative-strength-index': IndicatorInfo(
            ConnorsRelativeStrengthIndex(3, 2, 100), 
            'ConnorsRelativeStrengthIndex(3, 2, 100)', 
            'CRSI(_symbol, 3, 2, 100)', 
            'self.crsi(self._symbol, 3, 2, 100)'
        ),
        'coppock-curve': IndicatorInfo(
            CoppockCurve(11, 14, 10), 
            'CoppockCurve(11, 14, 10)', 
            'CC(_symbol, 11, 14, 10)',
            'self.cc(self._symbol, 11, 14, 10)'
        ),
        'demarker-indicator': IndicatorInfo(
            DeMarkerIndicator(20, MovingAverageType.SIMPLE),
            'DeMarkerIndicator(20, MovingAverageType.Simple)',
            'DEM(_symbol, 20, MovingAverageType.Simple)',
            'self.dem(self._symbol, 20, MovingAverageType.SIMPLE)'
        ),
        'tom-demark-sequential': IndicatorInfo(
            TomDemarkSequential(),
            'TomDemarkSequential()',
            'TDS(_symbol)',
            'self.tds(self._symbol)'
        ),
        'derivative-oscillator': IndicatorInfo(
            DerivativeOscillator(14, 5, 3, 9),
            'DerivativeOscillator(14, 5, 3, 9)',
            'DO(_symbol, 14, 5, 3, 9)',
            'self.do(self._symbol, 14, 5, 3, 9)'
        ),
        'detrended-price-oscillator': IndicatorInfo(
            DetrendedPriceOscillator(20),
            'DetrendedPriceOscillator(20)',
            'DPO(_symbol, 20)',
            'self.dpo(self._symbol, 20)'
        ),
        'donchian-channel': IndicatorInfo(
            DonchianChannel(20, 20), 
            'DonchianChannel(20, 20)', 
            'DCH(_symbol, 20, 20)',
            'self.dch(self._symbol, 20, 20)',
            [['upperband', 'lowerband']]
        ),
        'double-exponential-moving-average': IndicatorInfo(
            DoubleExponentialMovingAverage(20),
            'DoubleExponentialMovingAverage(20)',
            'DEMA(_symbol, 20)',
            'self.dema(self._symbol, 20)'
        ),
        'ease-of-movement-value': IndicatorInfo(
            EaseOfMovementValue(1, 10000),
            'EaseOfMovementValue(1, 10000)',
            'EMV(_symbol, 1, 10000)', 
            'self.emv(self._symbol, 1, 10000)'
        ),
        'exponential-moving-average': IndicatorInfo(
            ExponentialMovingAverage(20, 0.5),
            'ExponentialMovingAverage(20, 0.5m)',
            'EMA(_symbol, 20, 0.5m)',
            'self.ema(self._symbol, 20, 0.5)'
        ),
        'fisher-transform': IndicatorInfo(
            FisherTransform(20),
            'FisherTransform(20)',
            'FISH(_symbol, 20)', 
            'self.fish(self._symbol, 20)'
        ),
        'force-index': IndicatorInfo(
            ForceIndex(13),
            'ForceIndex(13)',
            'FI(_symbol, 13)',
            'self.fi(self._symbol, 13)'
        ),
        'fractal-adaptive-moving-average': IndicatorInfo(
            FractalAdaptiveMovingAverage(20, 198),
            'FractalAdaptiveMovingAverage(20, 198)',
            'FRAMA(_symbol, 20, 198)',
            'self.frama(self._symbol, 20, 198)'
        ),
        'heikin-ashi': IndicatorInfo(
            HeikinAshi(),
            'HeikinAshi()',
            'HeikinAshi(_symbol)',
            'self.heikin_ashi(self._symbol)', 
            [['open', 'high', 'low', 'close'], ['volume']]
        ),
        'hilbert-transform': IndicatorInfo(
            HilbertTransform(7, 0.635, 0.338),
            'HilbertTransform(7, 0.635m, 0.338m)',
            'HT(_symbol, 7, 0.635m, 0.338m)',
            'self.ht(self._symbol, 7, 0.635, 0.338)',
            [['inphase', 'quadrature']]
        ),
        'hull-moving-average': IndicatorInfo(
            HullMovingAverage(20),
            'HullMovingAverage(20)',
            'HMA(_symbol, 20)',
            'self.hma(self._symbol, 20)'
        ),
        'hurst-exponent': IndicatorInfo(
            HurstExponent(32),
            'HurstExponent(32)',
            'HE(_symbol, 32)',
            'self.he(self._symbol, 32)'
        ),
        'ichimoku-kinko-hyo': IndicatorInfo(
            IchimokuKinkoHyo(9, 26, 17, 52, 26, 26), 
            'IchimokuKinkoHyo(9, 26, 17, 52, 26, 26)', 
            'ICHIMOKU(_symbol, 9, 26, 17, 52, 26, 26)',
            'self.ichimoku(self._symbol, 9, 26, 17, 52, 26, 26)',
            [['chikou', 'kijun', 'senkoua', 'senkoub', 'tenkan']]
        ),
        'identity': IndicatorInfo(
            Identity(),
            'Identity()',
            'Identity(_symbol)',
            'self.identity(self._symbol)'
        ),
        'filtered-identity': IndicatorInfo(
            FilteredIdentity(filter=lambda x: x.close > x.open), 
            'FilteredIdentity(filter: x =>  (x as TradeBar).Close > (x as TradeBar).Open)', 
            'FilteredIdentity(_symbol, filter: x =>  (x as TradeBar).Close > (x as TradeBar).Open)', 
            'self.filtered_identity(self._symbol, filter=lambda x: x.close > x.open)'
        ),
        'internal-bar-strength': IndicatorInfo(
            InternalBarStrength(),
            'InternalBarStrength()', 
            'IBS(_symbol)',
            'self.ibs(self._symbol)'
        ),
        'kaufman-adaptive-moving-average': IndicatorInfo(
            KaufmanAdaptiveMovingAverage(20, 10, 20),
            'KaufmanAdaptiveMovingAverage(20, 10, 20)',
            'KAMA(_symbol, 20, 10, 20)',
            'self.kama(self._symbol, 20, 10, 20)'
        ),
        'kaufman-efficiency-ratio': IndicatorInfo(
            KaufmanEfficiencyRatio(20),
            'KaufmanEfficiencyRatio(20)',
            'KER(_symbol, 20)',
            'self.ker(self._symbol, 20)'
        ),
        'keltner-channels': IndicatorInfo(
            KeltnerChannels(20, 2, MovingAverageType.SIMPLE), 
            'KeltnerChannels(20, 2, MovingAverageType.Simple)', 
            'KCH(_symbol, 20, 2, MovingAverageType.Simple)', 
            'self.kch(self._symbol, 20, 2, MovingAverageType.SIMPLE)',
            [['upperband', 'middleband', 'lowerband'], ['averagetruerange']]
        ),
        'klinger-volume-oscillator': IndicatorInfo(
            KlingerVolumeOscillator(5, 10, 13), 
            'KlingerVolumeOscillator(5, 10, 13)', 
            'KVO(_symbol, 5, 10, 13)', 
            'self.kvo(self._symbol, 5, 10, 13)'
        ),
        'know-sure-thing': IndicatorInfo(
            KnowSureThing(5, 5, 10, 5, 15, 5, 25, 10, 9, MovingAverageType.SIMPLE), 
            'KnowSureThing(5, 5, 10, 5, 15, 5, 25, 10, 9, MovingAverageType.Simple)', 
            'KST(_symbol, 5, 5, 10, 5, 15, 5, 25, 10, 9, MovingAverageType.Simple)', 
            'self.kst(self._symbol, 5, 5, 10, 5, 15, 5, 25, 10, 9, MovingAverageType.SIMPLE)'
        ),
        'least-squares-moving-average': IndicatorInfo(
            LeastSquaresMovingAverage(20),
            'LeastSquaresMovingAverage(20)',
            'LSMA(_symbol, 20)',
            'self.lsma(self._symbol, 20)',
            [['current', 'intercept'], ['slope']]
        ),
        'linear-weighted-moving-average': IndicatorInfo(
            LinearWeightedMovingAverage(20),
            'LinearWeightedMovingAverage(20)', 
            'LWMA(_symbol, 20)', 
            'self.lwma(self._symbol, 20)'
        ),
        'log-return': IndicatorInfo(
            LogReturn(20),
            'LogReturn(20)',
            'LOGR(_symbol, 20)',
            'self.logr(self._symbol, 20)'
        ),
        'mass-index': IndicatorInfo(
            MassIndex(9, 25),
            'MassIndex(9, 25)',
            'MASS(_symbol, 9, 25)',
            'self.mass(self._symbol, 9, 25)'
        ),
        'maximum': IndicatorInfo(
            Maximum(20),
            'Maximum(20)',
            'MAX(_symbol, 20)',
            'self.max(self._symbol, 20)'
        ),
        'mcginley-dynamic': IndicatorInfo(
            McGinleyDynamic(10),
            'McGinleyDynamic(10)',
            'MGD(_symbol, 10)',
            'self.mgd(self._symbol, 10)'
        ),
        'mid-point': IndicatorInfo(
            MidPoint(20),
            'MidPoint(20)',
            'MIDPOINT(_symbol, 20)',
            'self.midpoint(self._symbol, 20)'
        ),
        'mid-price': IndicatorInfo(
            MidPrice(20),
            'MidPrice(20)',
            'MIDPRICE(_symbol, 20)',
            'self.midprice(self._symbol, 20)'
        ),
        'mean-absolute-deviation': IndicatorInfo(
            MeanAbsoluteDeviation(20),
            'MeanAbsoluteDeviation(20)',
            'MAD(_symbol, 20)',
            'self.mad(self._symbol, 20)',
            [['mean'], ['current']]
        ),
        'mesa-adaptive-moving-average': IndicatorInfo(
            MesaAdaptiveMovingAverage(0.5, 0.05),
            'MesaAdaptiveMovingAverage(0.5m, 0.05m)',
            'MAMA(_symbol, 0.5m, 0.05m)',
            'self.mama(self._symbol, 0.5, 0.05)',
            [['current', 'fama']]
        ),
        'minimum': IndicatorInfo(
            Minimum(20),
            'Minimum(20)',
            'MIN(_symbol, 20)',
            'self.min(self._symbol, 20)'
        ),
        'momentum': IndicatorInfo(
            Momentum(20),
            'Momentum(20)',
            'MOM(_symbol, 20)',
            'self.mom(self._symbol, 20)'
        ),
        'momentum-percent': IndicatorInfo(
            MomentumPercent(20),
            'MomentumPercent(20)',
            'MOMP(_symbol, 20)',
            'self.momp(self._symbol, 20)'
        ),
        'momersion': IndicatorInfo(
            Momersion(10, 20),
            'Momersion(10, 20)',
            'MOMERSION(_symbol, 10, 20)',
            'self.momersion(self._symbol, 10, 20)'
        ),
        'money-flow-index': IndicatorInfo(
            MoneyFlowIndex(20),
            'MoneyFlowIndex(20)',
            'MFI(_symbol, 20)',
            'self.mfi(self._symbol, 20)',
            [['current'], ['positivemoneyflow', 'negativemoneyflow']]
        ),
        'moving-average-convergence-divergence': IndicatorInfo(
            MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.EXPONENTIAL), 
            'MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.Exponential)', 
            'MACD(_symbol, 12, 26, 9, MovingAverageType.Exponential)', 
            'self.macd(self._symbol, 12, 26, 9, MovingAverageType.EXPONENTIAL)',
            [['fast', 'slow'], ['current', 'signal', 'histogram']]
        ),
        'normalized-average-true-range': IndicatorInfo(
            NormalizedAverageTrueRange(20),
            'NormalizedAverageTrueRange(20)',
            'NATR(_symbol, 20)',
            'self.natr(self._symbol, 20)'
        ),
        'on-balance-volume': IndicatorInfo(
            OnBalanceVolume(),
            'OnBalanceVolume()',
            'OBV(_symbol)', 
            'self.obv(self._symbol)'
        ),
        'parabolic-stop-and-reverse': IndicatorInfo(
            ParabolicStopAndReverse(0.02, 0.02, 0.2), 
            'ParabolicStopAndReverse(0.02m, 0.02m, 0.2m)', 
            'PSAR(_symbol, 0.02m, 0.02m, 0.2m)', 
            'self.psar(self._symbol, 0.02, 0.02, 0.2)'
        ),
        'parabolic-stop-and-reverse-extended': IndicatorInfo(
            ParabolicStopAndReverseExtended(0.0, 0.0, 0.02, 0.02, 0.2, 0.02, 0.02, 0.2), 
            'ParabolicStopAndReverseExtended(0.0m, 0.0m, 0.02m, 0.02m, 0.2m, 0.02m, 0.02m, 0.2m)', 
            'SAREXT(_symbol, 0.0m, 0.0m, 0.02m, 0.02m, 0.2m, 0.02m, 0.02m, 0.2m)', 
            'self.sarext(self._symbol, 0.0, 0.0, 0.02, 0.02, 0.2, 0.02, 0.02, 0.2)'
        ),
        'percentage-price-oscillator': IndicatorInfo(
            PercentagePriceOscillator(10, 20, MovingAverageType.SIMPLE), 
            'PercentagePriceOscillator(10, 20, MovingAverageType.Simple)', 
            'PPO(_symbol, 10, 20, MovingAverageType.Simple)', 
            'self.ppo(self._symbol, 10, 20, MovingAverageType.SIMPLE)',
            [['fast', 'slow'], ['current', 'signal', 'histogram']]
        ),
        'pivot-points-high-low': IndicatorInfo(
            PivotPointsHighLow(10, 10, 100), 
            'PivotPointsHighLow(10, 10, 100)', 
            'PPHL(_symbol, 10, 10, 100)', 
            'self.pphl(self._symbol, 10, 10, 100)'
        ),
        'premier-stochastic-oscillator': IndicatorInfo(
            PremierStochasticOscillator(14, 3), 
            'PremierStochasticOscillator(14, 3)', 
            'PSO(_symbol, 14, 3)', 
            'self.pso(self._symbol, 14, 3)'
        ),
        'rate-of-change': IndicatorInfo(
            RateOfChange(10), 
            'RateOfChange(10)', 
            'ROC(_symbol, 10)', 
            'self.roc(self._symbol, 10)'
        ),
        'rate-of-change-percent': IndicatorInfo(
            RateOfChangePercent(10), 
            'RateOfChangePercent(10)', 
            'ROCP(_symbol, 10)', 
            'self.rocp(self._symbol, 10)'
        ),
        'rate-of-change-ratio': IndicatorInfo(
            RateOfChangeRatio(10), 
            'RateOfChangeRatio(10)', 
            'ROCR(_symbol, 10)', 
            'self.rocr(self._symbol, 10)'
        ),
        'regression-channel': IndicatorInfo(
            RegressionChannel(20, 2), 
            'RegressionChannel(20, 2)', 
            'RC(_symbol, 20, 2)', 
            'self.rc(self._symbol, 20, 2)',
            [['upperchannel', 'current', 'lowerchannel', 'intercept'], ['slope']]
        ),
        'relative-daily-volume': IndicatorInfo(
            RelativeDailyVolume(2), 
            'RelativeDailyVolume(2)', 
            'RDV(_symbol, 2)', 
            'self.rdv(self._symbol, 2)'
        ),
        'relative-moving-average':  IndicatorInfo(  
            RelativeMovingAverage(20), 
            'RelativeMovingAverage(20)', 
            'RMA(_symbol, 20)', 
            'self.rma(self._symbol, 20)',
            [['current','shortaverage','mediumaverage','longaverage']]
        ), 
        'relative-strength-index': IndicatorInfo(
            RelativeStrengthIndex(14), 
            'RelativeStrengthIndex(14)', 
            'RSI(_symbol, 14)', 
            'self.rsi(self._symbol, 14)',
            [['current'], ['averagegain', 'averageloss']]
        ),
        'relative-vigor-index': IndicatorInfo(
            RelativeVigorIndex(20, MovingAverageType.SIMPLE),
            'RelativeVigorIndex(20, MovingAverageType.Simple)',
            'RVI(_symbol, 20, MovingAverageType.Simple)', 
            'self.rvi(self._symbol, 20, MovingAverageType.SIMPLE)'
        ),
        'rogers-satchell-volatility': IndicatorInfo(
            RogersSatchellVolatility(30), 
            'RogersSatchellVolatility(30)', 
            'RSV(_symbol, 30)', 
            'self.rsv(self._symbol, 30)'
        ),
        'schaff-trend-cycle': IndicatorInfo(
            SchaffTrendCycle(5, 10, 20, MovingAverageType.EXPONENTIAL), 
            'SchaffTrendCycle(5, 10, 20, MovingAverageType.EXPONENTIAL)',
            'STC(_symbol, 5, 10, 20, MovingAverageType.Exponential)', 
            'self.stc(self._symbol, 5, 10, 20, MovingAverageType.EXPONENTIAL)'
        ),
        'sharpe-ratio': IndicatorInfo(
            SharpeRatio(22, 0.03), 
            'SharpeRatio(22, 0.03)', 
            'SR(_symbol, 22, 0.03m)', 
            'self.sr(self._symbol, 22, 0.03)'
        ),
        'simple-moving-average': IndicatorInfo(
            SimpleMovingAverage(20),
            'SimpleMovingAverage(20)',
            'SMA(_symbol, 20)', 
            'self.sma(self._symbol, 20)'
        ),
        'smoothed-on-balance-volume': IndicatorInfo(
            SmoothedOnBalanceVolume(20), 
            'SmoothedOnBalanceVolume(20)', 
            'SOBV(_symbol, 20)', 
            'self.sobv(self._symbol, 20)'
        ),
        'sortino-ratio': IndicatorInfo(
            SortinoRatio(22),
            'SortinoRatio(22)',
            'SORTINO(_symbol, 22)',
            'self.sortino(self._symbol, 22)'
        ),
        'squeeze-momentum': IndicatorInfo(
            SqueezeMomentum(20, 2, 20, 1.5), 
            'SqueezeMomentum("SPY", 20, 2m, 20, 1.5m)', 
            'SM(_symbol, 20, 2, 20, 1.5m)', 
            'self.sm(self._symbol, 20, 2, 20, 1.5)'
        ),
        'standard-deviation': IndicatorInfo(
            StandardDeviation(22), 
            'StandardDeviation(22)', 
            'STD(_symbol, 22)', 
            'self.std(self._symbol, 22)'
        ),
        'stochastic': IndicatorInfo(
            Stochastic(20, 10, 20), 
            'Stochastic(20, 10, 20)', 
            'STO(_symbol, 20, 10, 20)',
            'self.sto(self._symbol, 20, 10, 20)',
            [['faststoch', 'stochd', 'stochk']]
        ),
        'stochastic-relative-strength-index': IndicatorInfo(
            StochasticRelativeStrengthIndex(14, 14, 3, 3), 
            'StochasticRelativeStrengthIndex(14, 14, 3, 3)', 
            'SRSI(_symbol, 14, 14, 3, 3)',
            'self.srsi(self._symbol, 14, 14, 3, 3)',
            [['current'], ["k", "d"]]
        ),
        'sum': IndicatorInfo(
            Sum(20), 
            'Sum(20)', 
            'SUM(_symbol, 20)', 
            'self.sum(self._symbol, 20)'
        ),
        'super-trend': IndicatorInfo(
            SuperTrend(20, 2, MovingAverageType.WILDERS), 
            'SuperTrend(20, 2, MovingAverageType.Wilders)', 
            'STR(_symbol, 20, 2, MovingAverageType.Wilders)', 
            'self.str(self._symbol, 20, 2, MovingAverageType.WILDERS)'
        ),
        'swiss-army-knife': IndicatorInfo(
            SwissArmyKnife(20, 0.2, SwissArmyKnifeTool.GAUSS), 
            'SwissArmyKnife(20, 0.2, SwissArmyKnifeTool.Gauss)', 
            'SWISS(_symbol, 20, 0.2, SwissArmyKnifeTool.Gauss)', 
            'self.swiss(self._symbol, 20, 0.2, SwissArmyKnifeTool.GAUSS)'
        ),
        't3-moving-average': IndicatorInfo(
            T3MovingAverage(30, 0.7), 
            'T3MovingAverage(30, 0.7m)', 
            'T3(_symbol, 30, 0.7m)', 
            'self.t_3(self._symbol, 30, 0.7)'
        ),
        'time-series-forecast': IndicatorInfo(
            TimeSeriesForecast(3), 
            'TimeSeriesForecast(3)', 
            'TSF(_symbol, 3)', 
            'self.tsf(self._symbol, 3)'
        ),
        'time-profile': IndicatorInfo(
            TimeProfile(3, 0.70, 0.05), 
            'TimeProfile(3, 0.70m, 0.05m)', 
            'TP(_symbol, 3, 0.70m, 0.05m)', 
            'self.tp(self._symbol, 3, 0.70, 0.05)'
        ),
        'volume-profile': IndicatorInfo(
            VolumeProfile(3, 0.70, 0.05), 
            'VolumeProfile(3, 0.70m, 0.05m)', 
            'VP(_symbol, 3, 0.70m, 0.05m)', 
            'self.vp(self._symbol, 3, 0.70, 0.05)'
        ),
        'triangular-moving-average': IndicatorInfo(
            TriangularMovingAverage(20), 
            'TriangularMovingAverage(20)', 
            'TRIMA(_symbol, 20)', 
            'self.trima(self._symbol, 20)'
        ),
        'triple-exponential-moving-average': IndicatorInfo(
            TripleExponentialMovingAverage(20), 
            'TripleExponentialMovingAverage(20)', 
            'TEMA(_symbol, 20)',
            'self.tema(self._symbol, 20)'
        ),
        'trix': IndicatorInfo(
            Trix(20), 
            'Trix(20)', 
            'TRIX(_symbol, 20)', 
            'self.trix(self._symbol, 20)'
        ),
        'true-range': IndicatorInfo(
            TrueRange(),
            'TrueRange()', 
            'TR(_symbol)', 
            'self.tr(self._symbol)'
        ),
        'true-strength-index': IndicatorInfo(
            TrueStrengthIndex(25, 13, 7, MovingAverageType.EXPONENTIAL), 
            'TrueStrengthIndex(25, 13, 7, MovingAverageType.Exponential)', 
            'TSI(_symbol, 25, 13, 7, MovingAverageType.Exponential)', 
            'self.tsi(self._symbol, 25, 13, 7, MovingAverageType.EXPONENTIAL)',
            [['current', 'signal']]
        ),
        'ultimate-oscillator': IndicatorInfo(
            UltimateOscillator(5, 10, 20),
            'UltimateOscillator(5, 10, 20)',  
            'ULTOSC(_symbol, 5, 10, 20)',
            'self.ultosc(self._symbol, 5, 10, 20)'
        ),
        'value-at-risk': IndicatorInfo(
            ValueAtRisk(252, 0.95),
            'ValueAtRisk(252, 0.95)',
            'VAR(_symbol, 252, 0.95)',
            'self.var(self._symbol, 252, 0.95)'
        ),
        'variable-index-dynamic-average': IndicatorInfo(
            VariableIndexDynamicAverage(20), 
            'VariableIndexDynamicAverage(20)', 
            'VIDYA(_symbol, 20)', 
            'self.vidya(self._symbol, 20)'
        ),
        'variance': IndicatorInfo(
            Variance(20), 
            'Variance(20)', 
            'V(_symbol, 20)', 
            'self.v(self._symbol, 20)'
        ),
        'volume-weighted-average-price-indicator': IndicatorInfo(
            VolumeWeightedAveragePriceIndicator(20), 
            'VolumeWeightedAveragePriceIndicator(20)', 
            'VWAP(_symbol, 20)', 
            'self.vwap(self._symbol, 20)'
        ),
        'volume-weighted-moving-average': IndicatorInfo(
            VolumeWeightedMovingAverage(20), 
            'VolumeWeightedMovingAverage(20)', 
            'VWMA(_symbol, 20)', 
            'self.vwma(self._symbol, 20)'
        ),
        'vortex': IndicatorInfo(
            Vortex(14), 
            'Vortex(14)', 
            'VTX(_symbol, 14)', 
            'self.vtx(self._symbol, 14)',
            [['current', "plusvortex", "minusvortex"]]
        ),
        'wilder-accumulative-swing-index': IndicatorInfo(
            WilderAccumulativeSwingIndex(20), 
            'WilderAccumulativeSwingIndex(20)', 
            'ASI(_symbol, 20)', 
            'self.asi(self._symbol, 20, Resolution.DAILY)'
        ),
        'wilder-moving-average': IndicatorInfo(
            WilderMovingAverage(20),
            'WilderMovingAverage(20)',  
            'WWMA(_symbol, 20)', 
            'self.wwma(self._symbol, 20)'
        ),
        'wilder-swing-index': IndicatorInfo(
            WilderSwingIndex(20), 
            'WilderSwingIndex(20)', 
            'SI(_symbol, 20)', 
            'self.si(self._symbol, 20, Resolution.DAILY)'
        ),
        'williams-percent-r': IndicatorInfo(
            WilliamsPercentR(20), 
            'WilliamsPercentR(20)', 
            'WILR(_symbol, 20)', 
            'self.wilr(self._symbol, 20)'
        ),
        'zero-lag-exponential-moving-average': IndicatorInfo(
            ZeroLagExponentialMovingAverage(10), 
            'ZeroLagExponentialMovingAverage(10)', 
            'ZLEMA(_symbol, 10)', 
            'self.zlema(self._symbol, 10)'
        ),
        'zig-zag': IndicatorInfo(
            ZigZag(0.05, 1), 
            'ZigZag(0.05m, 1)', 
            'ZZ(_symbol, 0.05m, 1)', 
            'self.zz(self._symbol, 0.05, 1)'
        ),
        # See https://github.com/QuantConnect/Lean/issues/8808
        'target-downside-deviation': IndicatorInfo(
            IndicatorExtensions.of(TargetDownsideDeviation(50), RateOfChange(1)), 
            'TargetDownsideDeviation(50).Of(new RateOfChange(1))',
            'TDD(_symbol, 50)', 
            'self.tdd(self._symbol, 50)'
        )
    }

    def get_special_indicators(self) -> dict[str,IndicatorInfo]:
        return {
        'advance-decline-difference': IndicatorInfo(
            AdvanceDeclineDifference(), 
            'AdvanceDeclineDifference()', 
            'ADDIFF([_symbol, _reference])', 
            'self.addiff([self._symbol, self._reference])'
        ),
        'advance-decline-ratio': IndicatorInfo(
            AdvanceDeclineRatio(), 
            'AdvanceDeclineRatio()', 
            'ADR([_symbol, _reference])', 
            'self.adr([self._symbol, self._reference])'
        ),
        'advance-decline-volume-ratio': IndicatorInfo(
            AdvanceDeclineVolumeRatio(), 
            'AdvanceDeclineVolumeRatio()', 
            'ADVR([_symbol, _reference])', 
            'self.advr([self._symbol, self._reference])'
        ),
        'arms-index': IndicatorInfo(
            ArmsIndex(), 
            'ArmsIndex()', 
            'TRIN([_symbol, _reference])', 
            'self.trin([self._symbol, self._reference])'
        ),
        'alpha': IndicatorInfo(
            Alpha(self._symbol, self._reference, 20), 
            'Alpha(_symbol, _reference, 20)', 
            'A(_symbol, _reference, 20)', 
            'self.a(self._symbol, self._reference, 20)'
        ),
        'beta': IndicatorInfo(
            Beta(self._symbol, self._reference, 20), 
            'Beta(_symbol, _reference, 20)', 
            'B(_symbol, _reference, 20)', 
            'self.b(self._symbol, self._reference, 20)'
        ),
        'correlation': IndicatorInfo(
            Correlation(self._symbol, self._reference, 20, CorrelationType.PEARSON), 
            'Correlation(_symbol, _reference, 20, CorrelationType.Pearson)', 
            'C(_symbol, _reference, 20, CorrelationType.Pearson)', 
            'self.c(self._symbol, self._reference, 20, CorrelationType.PEARSON)'
        ),
        'mcclellan-oscillator': IndicatorInfo(
            McClellanOscillator(19, 39), 
            'McClellanOscillator(19, 39)', 
            'MOSC([_symbol, _reference], 19, 39)', 
            'self.mosc([self._symbol, self._reference], 19, 39)'
        ),
        'mcclellan-summation-index': IndicatorInfo(
            McClellanSummationIndex(19, 39), 
            'McClellanSummationIndex(19, 39)', 
            'MSI([_symbol, _reference])', 
            'self.msi([self._symbol, self._reference])'
        )
    }

    def get_option_indicators(self, interest_rate_model, dividend_yield_model) -> dict[str,IndicatorInfo]:
        return {
        'implied-volatility': IndicatorInfo(
            ImpliedVolatility(self._option, interest_rate_model, dividend_yield_model, self._mirror_option),
            'ImpliedVolatility(_option, interestRateModel, dividendYieldModel, _mirrorOption)',
            'IV(_option, _mirrorOption)', 
            'self.iv(self._option, self._mirror_option)'
        ),
        'delta': IndicatorInfo(
            Delta(self._option, interest_rate_model, dividend_yield_model, self._mirror_option),
            'Delta(_option, interestRateModel, dividendYieldModel, _mirrorOption)',
            'D(_option, _mirrorOption)', 
            'self.d(self._option, self._mirror_option)'
        ),
        'gamma': IndicatorInfo(
            Gamma(self._option, interest_rate_model, dividend_yield_model, self._mirror_option), 
            'Gamma(_option, interestRateModel, dividendYieldModel, _mirrorOption)', 
            'G(_option, _mirrorOption)', 
            'self.g(self._option, self._mirror_option)'
        ),
        'vega': IndicatorInfo(
            Vega(self._option, interest_rate_model, dividend_yield_model, self._mirror_option),
            'Vega(_option, interestRateModel, dividendYieldModel, _mirrorOption)',
            'V(_option, _mirrorOption)', 
            'self.v(self._option, self._mirror_option)'
        ),
        'theta': IndicatorInfo(
            Theta(self._option, interest_rate_model, dividend_yield_model, self._mirror_option), 
            'Theta(_option, interestRateModel, dividendYieldModel, _mirrorOption)', 
            'T(_option, _mirrorOption)', 
            'self.t(self._option, self._mirror_option)'
        ),
        'rho': IndicatorInfo(
            Rho(self._option, interest_rate_model, dividend_yield_model, self._mirror_option), 
            'Rho(_option, interestRateModel, dividendYieldModel, _mirrorOption)', 
            'R(_option, _mirrorOption)', 
            'self.r(self._option, self._mirror_option)'
        )
    }
