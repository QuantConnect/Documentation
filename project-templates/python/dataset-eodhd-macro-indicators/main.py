# region imports
from AlgorithmImports import *
# endregion


class EODHDMacroGDPGrowthRotationAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2018, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        # Pair each country's GDP-growth indicator with a tradable country ETF.
        indicator_by_etf = {
            "SPY": EODHD.MacroIndicators.UnitedStates.GDP_GROWTH_ANNUAL,
            "EWJ": EODHD.MacroIndicators.Japan.GDP_GROWTH_ANNUAL,
            "EWG": EODHD.MacroIndicators.Germany.GDP_GROWTH_ANNUAL,
            "EWU": EODHD.MacroIndicators.UnitedKingdom.GDP_GROWTH_ANNUAL,
            "EWA": EODHD.MacroIndicators.Australia.GDP_GROWTH_ANNUAL,
            "EWC": EODHD.MacroIndicators.Canada.GDP_GROWTH_ANNUAL,
            "MCHI": EODHD.MacroIndicators.China.GDP_GROWTH_ANNUAL,
            "INDA": EODHD.MacroIndicators.India.GDP_GROWTH_ANNUAL
        }
        # Daily resolution on the ETFs is plenty for an annual rebalance cadence.
        self._etfs = []
        for ticker, indicator in indicator_by_etf.items():
            etf = self.add_equity(ticker, Resolution.HOUR)
            etf.indicator_symbol = self.add_data(EODHDMacroIndicators, indicator).symbol
            self._etfs.append(etf)

    def on_data(self, data: Slice) -> None:
        # Most slices carry no new macro reading; only react when at least one fires.
        gdp_growth_by_etf = {}
        for etf in self._etfs:
            indicators = data.get(EODHDMacroIndicators).get(etf.indicator_symbol)
            if not indicators:
                continue
            # A single release can deliver several points (revisions); keep the last value.
            for point in indicators.data:
                gdp_growth_by_etf[etf] = point.value
        if not gdp_growth_by_etf:
            return
        # Long the 3 fastest-growing economies' ETFs, equal-weighted.
        etfs = sorted(gdp_growth_by_etf, key=lambda etf: gdp_growth_by_etf[etf])[-3:]
        weight = 1.0 / len(etfs)
        targets = [PortfolioTarget(etf, weight) for etf in etfs]
        self.set_holdings(targets, True)
