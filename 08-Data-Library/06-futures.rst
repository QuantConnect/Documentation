.. _data-library-futures:

=======
Futures
=======

|

Introduction
============

QuantConnect provides US Future prices for all assets listed on the CME, CBOT, NYMEX, and Comex since May 2009. Data is timestamped to the millisecond. It is also survivorship-bias free (includes symbols no longer traded). Futures live trading only available through Interactive Brokers.

.. list-table:: Data Properties
   :header-rows: 0

   * - Data Provider
     - `AlgoSeek <https://www.quantconnect.com/data/provider/algoseek>`_

   * - Start Date
     - Data is available starting May 1st, 2009

   * - Symbol Universe
     - ≈ 1100 Symbols. All symbols trading on future exchanges each day.

   * - Data Type
     - Trades and Quotes

   * - Resolutions
     - 	Tick, Second, Minute

Future data comes as trades and quotes, with tick, second, and minute resolutions available.

Live Trading futures data is sourced from Interactive Brokers. The data used comes from *your* futures data subscription. You must have a valid `data subscription <https://www.interactivebrokers.com/en/software/am/am/manageaccount/marketdatasubscriptions.htm>`_ with Interactive Brokers.

|

Requesting Futures Data
=======================

To subscribe to futures data in QuantConnect, you should first select the asset you'd like to trade and then specify the filter by the contract's expiration that you'd like to see.

.. code-block::

    // Complete Add Future API - Including Default Parameters:
    AddFuture(string symbol,
          Resolution resolution = Resolution.Minute,
          string market = null,
          bool fillDataForward = true,
          decimal leverage = 0m)

By default, the futures universe is filtered down to contracts that expire within 35 days. A different set of contracts can be chosen with the ``SetFilter`` method:

.. tabs::

   .. code-tab:: c#

        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(TimeSpan.FromDays(30), TimeSpan.FromDays(180));

   .. code-tab:: py

        future = self.AddFuture(Futures.Indices.SP500EMini)
        future.SetFilter(timedelta(30), timedelta(182))

We can also use Linq in C# or use the filter function in Python on the contract list to select the contract(s) we want to trade:

.. tabs::

   .. code-tab:: c#

        // In Initialize
        var future = AddFuture(Futures.Indices.SP500EMini, Resolution.Minute);
        future.SetFilter(TimeSpan.Zero, TimeSpan.FromDays(182));
        // or filtering with Linq:
        future.SetFilter(universe => universe.Expiration(TimeSpan.Zero, TimeSpan.FromDays(182)));

   .. code-tab:: py

        # In Initialize
        future = self.AddFuture(Futures.Indices.SP500EMini, Resolution.Minute)
        future.SetFilter(timedelta(0), timedelta(182))
        # or with a Lambda Function:
        future.SetFilter(lambda universe: universe.Expiration(timedelta(0), timedelta(182)))

|

Using Futures Data
==================

Quotes and trades for your selected future contracts can be accessed in the Slice object in the OnData event handler. The ``FuturesChains`` member contains a ``FutureChain`` object for each subscribed future. A FutureChain is a collection of individual future contracts with different expiry dates.

.. tabs::

   .. code-tab:: c#

        // Save accessor symbol in Initialize() function.
        futureSymbol = future.Symbol;

        // In OnData(Slice slice)
        FuturesChain chain;
        // Explore the future contract chain
        if (slice.FuturesChains.TryGetValue(futureSymbol, out chain))
        {
            var underlying = chain.Underlying;
            var contracts = chain.Contracts.Value;
            foreach (var contract in contracts)
            {
                //
            }
        }

   .. code-tab:: py

        # Explore the future contract chain
        def OnData(self, slice):
            for chain in slice.FutureChains.Values:
                contracts = chain.Contracts
                for contract in contracts.Values:
                    pass

Future contracts have the following properties:

.. tabs::

   .. code-tab:: c#

        public class FuturesContract
        {
            Symbol Symbol;
            Symbol UnderlyingSymbol;
            DateTime Expiry;
            DateTime Time;
            decimal OpenInterest;
            decimal LastPrice;
            long Volume;
            decimal BidPrice;
            long BidSize;
            decimal AskPrice;
            long AskSize;
        }

   .. code-tab:: py

        class FuturesContract:
            self.Symbol # (Symbol) Symbol for contract needed to trade.
            self.UnderlyingSymbol # (Symbol) Underlying futures asset.
            self.Expiry # (datetime) When the future expires
            self.OpenInterest # (decimal) Number of open interest.
            self.LastPrice # (decimal) Last sale price.
            self.Volume # (long) reported volume.
            self.BidPrice # (decimal) bid quote price.
            self.BidSize # (long) bid quote size.
            self.AskPrice # (decimal) ask quote price.
            self.AskSize # (long) ask quote size.

|

Timezone
========

Algoseek futures data is set in the timezone in which the future is listed. The futures listed in CME or CBOT have their data set in Chicago Time, and the futures listed in NYMEX and Comex have their data set in New York Time. So when accessing futures data, make sure to account for the different time zones.

|

.. _data-library-futures-reference-table:

Reference Tables
================

The following reference table lists the Future assets available for use on QuantConnect. They can be requested using either the short code ticker or the helper static class below.

.. list-table:: Grains
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Black Sea Corn Financially Settled (Platts) Futures
     - ``Futures.Grains.BlackSeaCornFinanciallySettledPlatts``

   * - Black Sea Wheat Financially Settled (Platts) Futures
     - ``Futures.Grains.BlackSeaWheatFinanciallySettledPlatts``

   * - Chicago SRW Wheat Futures
     - ``Futures.Grains.SRWWheat``

   * - Default wheat contract is SRWWheat
     - ``Futures.Grains.Wheat``

   * - KC HRW Wheat Futures
     - ``Futures.Grains.HRWWheat``

   * - Corn Futures
     - ``Futures.Grains.Corn``

   * - Soybeans Futures
     - ``Futures.Grains.Soybeans``

   * - Soybean Meal Futures
     - ``Futures.Grains.SoybeanMeal``

   * - Soybean Oil Futures
     - ``Futures.Grains.SoybeanOil``

   * - Oats Futures
     - ``Futures.Grains.Oats``

.. list-table:: Currencies
   :header-rows: 1

   * - Name
     - Accessor Code

   * - British Pound Futures
     - ``Futures.Currencies.GBP``

   * - Canadian Dollar Futures
     - ``Futures.Currencies.CAD``

   * - Japanese Yen Futures
     - ``Futures.Currencies.JPY``

   * - Swiss Franc Futures
     - ``Futures.Currencies.CHF``

   * - Euro FX Futures
     - ``Futures.Currencies.EUR``

   * - Australian Dollar Futures
     - ``Futures.Currencies.AUD``

   * - New Zealand Dollar Futures
     - ``Futures.Currencies.NZD``

   * - Russian Ruble Futures
     - ``Futures.Currencies.RUB``

   * - Brazillian Real Futures
     - ``Futures.Currencies.BRL``

   * - Mexican Peso Futures
     - ``Futures.Currencies.MXN``

   * - South African Rand Futures
     - ``Futures.Currencies.ZAR``

   * - Australian Dollar/Canadian Dollar Futures
     - ``Futures.Currencies.AUDCAD``

   * - Australian Dollar/Japanese Yen Futures
     - ``Futures.Currencies.AUDJPY``

   * - Australian Dollar/New Zealand Dollar Futures
     - ``Futures.Currencies.AUDNZD``

   * - Bitcoin Futures
     - ``Futures.Currencies.BTC``

   * - Canadian Dollar/Japanese Yen Futures
     - ``Futures.Currencies.CADJPY``

   * - Standard-Size USD/Offshore RMB (CNH) Futures
     - ``Futures.Currencies.StandardSizeUSDOffshoreRMBCNH``

   * - E-mini Euro FX Futures
     - ``Futures.Currencies.EuroFXEmini``

   * - Euro/Australian Dollar Futures
     - ``Futures.Currencies.EURAUD``

   * - Euro/Canadian Dollar Futures
     - ``Futures.Currencies.EURCAD``

   * - Euro/Swedish Krona Futures
     - ``Futures.Currencies.EURSEK``

   * - E-mini Japanese Yen Futures
     - ``Futures.Currencies.JapaneseYenEmini``

.. list-table:: Energies
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Propane Non LDH Mont Belvieu (OPIS) BALMO Futures
     - ``Futures.Energies.PropaneNonLDHMontBelvieu``

   * - Argus Propane Far East Index BALMO Futures
     - ``Futures.Energies.ArgusPropaneFarEastIndexBALMO``

   * - Mini European 3.5% Fuel Oil Barges FOB Rdam (Platts) Futures
     - ``Futures.Energies.MiniEuropeanThreePointPercentFiveFuelOilBargesPlatts``

   * - Mini Singapore Fuel Oil 180 cst (Platts) Futures
     - ``Futures.Energies.MiniSingaporeFuelOil180CstPlatts``

   * - Gulf Coast ULSD (Platts) Up-Down BALMO Futures
     - ``Futures.Energies.GulfCoastULSDPlattsUpDownBALMO``

   * - Gulf Coast Jet (Platts) Up-Down BALMO Futures
     - ``Futures.Energies.GulfCoastJetPlattsUpDownBALMO``

   * - Propane Non-LDH Mont Belvieu (OPIS) Futures
     - ``Futures.Energies.PropaneNonLDHMontBelvieuOPIS``

   * - European Propane CIF ARA (Argus) BALMO Futures
     - ``Futures.Energies.EuropeanPropaneCIFARAArgusBALMO``

   * - Premium Unleaded Gasoline 10 ppm FOB MED (Platts) Futures
     - ``Futures.Energies.PremiumUnleadedGasoline10ppmFOBMEDPlatts``

   * - Argus Propane Far East Index Futures
     - ``Futures.Energies.ArgusPropaneFarEastIndex``

   * - Gasoline Euro-bob Oxy NWE Barges (Argus) Crack Spread BALMO Futures
     - ``Futures.Energies.GasolineEurobobOxyNWEBargesArgusCrackSpreadBALMO``

   * - Mont Belvieu Natural Gasoline (OPIS) Futures
     - ``Futures.Energies.MontBelvieuNaturalGasolineOPIS``

   * - Mont Belvieu Normal Butane (OPIS) BALMO Futures
     - ``Futures.Energies.MontBelvieuNormalButaneOPISBALMO``

   * - Conway Propane (OPIS) Futures
     - ``Futures.Energies.ConwayPropaneOPIS``

   * - Mont Belvieu LDH Propane (OPIS) BALMO Futures
     - ``Futures.Energies.MontBelvieuLDHPropaneOPISBALMO``

   * - Argus Propane Far East Index vs. European Propane CIF ARA (Argus) Futures
     - ``Futures.Energies.ArgusPropaneFarEastIndexVsEuropeanPropaneCIFARAArgus``

   * - Argus Propane (Saudi Aramco) Futures
     - ``Futures.Energies.ArgusPropaneSaudiAramco``

   * - Group Three ULSD (Platts) vs. NY Harbor ULSD Futures
     - ``Futures.Energies.GroupThreeULSDPlattsVsNYHarborULSD``

   * - Group Three Sub-octane Gasoliine (Platts) vs. RBOB Futures
     - ``Futures.Energies.GroupThreeSuboctaneGasolinePlattsVsRBOB``

   * - Singapore Fuel Oil 180 cst (Platts) BALMO Futures
     - ``Futures.Energies.SingaporeFuelOil180cstPlattsBALMO``

   * - Singapore Fuel Oil 380 cst (Platts) BALMO Futures
     - ``Futures.Energies.SingaporeFuelOil380cstPlattsBALMO``

   * - Mont Belvieu Ethane (OPIS) Futures
     - ``Futures.Energies.MontBelvieuEthaneOPIS``

   * - Mont Belvieu Normal Butane (OPIS) Futures
     - ``Futures.Energies.MontBelvieuNormalButaneOPIS``

   * - Brent Crude Oil vs. Dubai Crude Oil (Platts) Futures
     - ``Futures.Energies.BrentCrudeOilVsDubaiCrudeOilPlatts``

   * - Argus LLS vs. WTI (Argus) Trade Month Futures
     - ``Futures.Energies.ArgusLLSvsWTIArgusTradeMonth``

   * - Singapore Gasoil (Platts) vs. Low Sulphur Gasoil Futures
     - ``Futures.Energies.SingaporeGasoilPlattsVsLowSulphurGasoilFutures``

   * - Los Angeles CARBOB Gasoline (OPIS) vs. RBOB Gasoline Futures
     - ``Futures.Energies.LosAngelesCARBOBGasolineOPISvsRBOBGasoline``

   * - Los Angeles Jet (OPIS) vs. NY Harbor ULSD Futures
     - ``Futures.Energies.LosAngelesJetOPISvsNYHarborULSD``

   * - Los Angeles CARB Diesel (OPIS) vs. NY Harbor ULSD Futures
     - ``Futures.Energies.LosAngelesCARBDieselOPISvsNYHarborULSD``

   * - European Naphtha (Platts) BALMO Futures
     - ``Futures.Energies.EuropeanNaphthaPlattsBALMO``

   * - European Propane CIF ARA (Argus) Futures
     - ``Futures.Energies.EuropeanPropaneCIFARAArgus``

   * - Mont Belvieu Natural Gasoline (OPIS) BALMO Futures
     - ``Futures.Energies.MontBelvieuNaturalGasolineOPISBALMO``

   * - RBOB Gasoline Crack Spread Futures
     - ``Futures.Energies.RBOBGasolineCrackSpread``

   * - Gulf Coast HSFO (Platts) BALMO Futures
     - ``Futures.Energies.GulfCoastHSFOPlattsBALMO``

   * - Mars (Argus) vs. WTI Trade Month Futures
     - ``Futures.Energies.MarsArgusVsWTITradeMonth``

   * - Mars (Argus) vs. WTI Financial Futures
     - ``Futures.Energies.MarsArgusVsWTIFinancial``

   * - Ethanol T2 FOB Rdam Including Duty (Platts) Futures
     - ``Futures.Energies.EthanolT2FOBRdamIncludingDutyPlatts``

   * - Mont Belvieu LDH Propane (OPIS) Futures
     - ``Futures.Energies.MontBelvieuLDHPropaneOPIS``

   * - Gasoline Euro-bob Oxy NWE Barges (Argus) Futures
     - ``Futures.Energies.GasolineEurobobOxyNWEBargesArgus``

   * - WTI-Brent Financial Futures
     - ``Futures.Energies.WTIBrentFinancial``

   * - 3.5% Fuel Oil Barges FOB Rdam (Platts) Crack Spread (1000mt) Futures
     - ``Futures.Energies.ThreePointFivePercentFuelOilBargesFOBRdamPlattsCrackSpread1000mt``

   * - Gasoline Euro-bob Oxy NWE Barges (Argus) BALMO Futures
     - ``Futures.Energies.GasolineEurobobOxyNWEBargesArgusBALMO``

   * - Brent Last Day Financial Futures
     - ``Futures.Energies.BrentLastDayFinancial``

   * - Crude Oil WTI Futures
     - ``Futures.Energies.CrudeOilWTI``

   * - Gulf Coast CBOB Gasoline A2 (Platts) vs. RBOB Gasoline Futures
     - ``Futures.Energies.GulfCoastCBOBGasolineA2PlattsVsRBOBGasoline``

   * - Clearbrook Bakken Sweet Crude Oil Monthly Index (Net Energy) Futures
     - ``Futures.Energies.ClearbrookBakkenSweetCrudeOilMonthlyIndexNetEnergy``

   * - WTI Financial Futures
     - ``Futures.Energies.WTIFinancial``

   * - Chicago Ethaanol (Platts) Futures
     - ``Futures.Energies.ChicagoEthanolPlatts``

   * - Singapore Mogas 92 Unleaded (Platts) Brent Crack Spread Futures
     - ``Futures.Energies.SingaporeMogas92UnleadedPlattsBrentCrackSpread``

   * - Dubai Crude Oil (Platts) Financial Futures
     - ``Futures.Energies.DubaiCrudeOilPlattsFinancial``

   * - Japan C&amp;F Naphtha (Platts) BALMO Futures
     - ``Futures.Energies.JapanCnFNaphthaPlattsBALMO``

   * - Ethanol Futures
     - ``Futures.Energies.Ethanol``

   * - European Naphtha (Platts) Crack Spread Futures
     - ``Futures.Energies.EuropeanNaphthaPlattsCrackSpread``

   * - European Propane CIF ARA (Argus) vs. Naphtha Cargoes CIF NWE (Platts) Futures
     - ``Futures.Energies.EuropeanPropaneCIFARAArgusVsNaphthaCargoesCIFNWEPlatts``

   * - Singapore Fuel Oil 380 cst (Platts) vs. European 3.5% Fuel Oil Barges FOB Rdam (Platts) Futures
     - ``Futures.Energies.SingaporeFuelOil380cstPlattsVsEuropeanThreePointFivePercentFuelOilBargesFOBRdamPlatts``

   * - East-West Gasoline Spread (Platts-Argus) Futures
     - ``Futures.Energies.EastWestGasolineSpreadPlattsArgus``

   * - East-West Naphtha: Japan C&amp;F vs. Cargoes CIF NWE Spread (Platts) Futures
     - ``Futures.Energies.EastWestNaphthaJapanCFvsCargoesCIFNWESpreadPlatts``

   * - RBOB Gasoline vs. Euro-bob Oxy NWE Barges (Argus) (350,000 gallons) Futures
     - ``Futures.Energies.RBOBGasolineVsEurobobOxyNWEBargesArgusThreeHundredFiftyThousandGallons``

   * - 3.5% Fuel Oil Barges FOB Rdam (Platts) Crack Spread Futures
     - ``Futures.Energies.ThreePointFivePercentFuelOilBargesFOBRdamPlattsCrackSpread``

   * - Freight Route TC14 (Baltic) Futures
     - ``Futures.Energies.FreightRouteTC14Baltic``

   * - 1% Fuel Oil Cargoes FOB NWE (Platts) vs. 3.5% Fuel Oil Barges FOB Rdam (Platts) Futures
     - ``Futures.Energies.OnePercentFuelOilCargoesFOBNWEPlattsVsThreePointFivePercentFuelOilBargesFOBRdamPlatts``

   * - Gulf Coast HSFO (Platts) vs. European 3.5% Fuel Oil Barges FOB Rdam (Platts) Futures
     - ``Futures.Energies.GulfCoastHSFOPlattsVsEuropeanThreePointFivePercentFuelOilBargesFOBRdamPlatts``

   * - WTI Houston Crude Oil Futures
     - ``Futures.Energies.WTIHoustonCrudeOil``

   * - Natural Gas (Henry Hub) Last-day Financial Futures
     - ``Futures.Energies.NaturalGasHenryHubLastDayFinancial``

   * - Heating Oil Futures
     - ``Futures.Energies.HeatingOil``

   * - Natural Gas (Henry Hub) Penultimate Financial Futures
     - ``Futures.Energies.NaturalGasHenryHubPenultimateFinancial``

   * - WTI Houston (Argus) vs. WTI Trade Month Futures
     - ``Futures.Energies.WTIHoustonArgusVsWTITradeMonth``

   * - Gasoline RBOB Futures
     - ``Futures.Energies.Gasoline``

   * - Natural Gas Futures
     - ``Futures.Energies.NaturalGas``

.. list-table:: Financials
   :header-rows: 1

   * - Name
     - Accessor Code

   * - 30Y U.S. Treasury Bond Futures
     - ``Futures.Financials.Y30TreasuryBond``

   * - 10Y U.S. Treasury Note Futures
     - ``Futures.Financials.Y10TreasuryNote``

   * - 5Y U.S. Treasury Note Futures
     - ``Futures.Financials.Y5TreasuryNote``

   * - 2Y U.S. Treasury Note Futures
     - ``Futures.Financials.Y2TreasuryNote``

   * - EuroDollar Futures
     - ``Futures.Financials.EuroDollar``

   * - 5-Year USD MAC Swap Futures
     - ``Futures.Financials.FiveYearUSDMACSwap``

   * - Ultra U.S. Treasury Bond Futures
     - ``Futures.Financials.UltraUSTreasuryBond``

   * - Ultra 10-Year U.S. Treasury Note Futures
     - ``Futures.Financials.UltraTenYearUSTreasuryNote``

.. list-table:: Indices
   :header-rows: 1

   * - Name
     - Accessor Code

   * - E-mini S&amp;P 500 Futures
     - ``Futures.Indices.SP500EMini``

   * - E-mini NASDAQ 100 Futures
     - ``Futures.Indices.NASDAQ100EMini``

   * - E-mini Dow Indu 30 Futures
     - ``Futures.Indices.Dow30EMini``

   * - CBOE Volatility Index Futures
     - ``Futures.Indices.VIX``

   * - E-mini Russell 2000 Futures
     - ``Futures.Indices.Russell2000EMini``

   * - Nikkei-225 Dollar Futures
     - ``Futures.Indices.Nikkei225Dollar``

   * - Bloomberg Commodity Index Futures
     - ``Futures.Indices.BloombergCommodityIndex``

   * - E-mini Nasdaq-100 Biotechnology Index Futures
     - ``Futures.Indices.NASDAQ100BiotechnologyEMini``

   * - E-mini FTSE Emerging Index Futures
     - ``Futures.Indices.FTSEEmergingEmini``

   * - E-mini S&amp;P MidCap 400 Futures
     - ``Futures.Indices.SP400MidCapEmini``

   * - S&amp;P-GSCI Commodity Index Futures
     - ``Futures.Indices.SPGSCICommodity``

   * - USD-Denominated Ibovespa Index Futures
     - ``Futures.Indices.USDDenominatedIbovespa``

.. list-table:: Forestry
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Random Length Lumber Futures
     - ``Futures.Forestry.RandomLengthLumber``

.. list-table:: Meats
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Live Cattle Futures
     - ``Futures.Meats.LiveCattle``

   * - Feeder Cattle Futures
     - ``Futures.Meats.FeederCattle``

   * - Lean Hogs Futures
     - ``Futures.Meats.LeanHogs``

.. list-table:: Metals
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Gold Futures
     - ``Futures.Metals.Gold``

   * - Silver Futures
     - ``Futures.Metals.Silver``

   * - Platinum Futures
     - ``Futures.Metals.Platinum``

   * - Palladium Futures
     - ``Futures.Metals.Palladium``

   * - Aluminum MW U.S. Transaction Premium Platts (25MT) Futures
     - ``Futures.Metals.AluminumMWUSTransactionPremiumPlatts25MT``

   * - Aluminium European Premium Duty-Paid (Metal Bulletin) Futures
     - ``Futures.Metals.AluminiumEuropeanPremiumDutyPaidMetalBulletin``

   * - Copper Futures
     - ``Futures.Metals.Copper``

   * - U.S. Midwest Domestic Hot-Rolled Coil Steel (CRU) Index Futures
     - ``Futures.Metals.USMidwestDomesticHotRolledCoilSteelCRUIndex``

.. list-table:: Softs
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Sugar #11 Futures CME
     - ``Futures.Softs.Sugar11CME``

   * - Cocoa Futures
     - ``Futures.Softs.Cocoa``

.. list-table:: Dairy
   :header-rows: 1

   * - Name
     - Accessor Code

   * - Cash-settled Butter Futures
     - ``Futures.Dairy.CashSettledButter``

   * - Cash-settled Cheese Futures
     - ``Futures.Dairy.CashSettledCheese``

   * - Class III Milk Futures
     - ``Futures.Dairy.ClassIIIMilk``

   * - Dry Whey Futures
     - ``Futures.Dairy.DryWhey``

   * - Class IV Milk Futures
     - ``Futures.Dairy.ClassIVMilk``

   * - Non-fat Dry Milk Futures
     - ``Futures.Dairy.NonfatDryMilk``

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/web/i/providers/algoseek.png

`AlgoSeek <https://www.algoseek.com/>`_ is a leading provider of historical intraday US market data to banks, hedge funds, academia, and individuals worldwide. Their high quality and affordable datasets are used for research and trading around the world.

AlgoSeek has been collecting US Equities and ETF data on all listed USA equities and ETFs since January 2007. Their data is ready for institutional researchers for backtesting and quant research. Data is timestamped to the millisecond.