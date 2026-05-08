#region imports
    using System;
    using System.Collections;
    using System.Collections.Generic;
    using System.Linq;
    using System.Globalization;
    using System.Drawing;
    using QuantConnect;
    using QuantConnect.Algorithm.Framework;
    using QuantConnect.Algorithm.Framework.Selection;
    using QuantConnect.Algorithm.Framework.Alphas;
    using QuantConnect.Algorithm.Framework.Portfolio;
    using QuantConnect.Algorithm.Framework.Portfolio.SignalExports;
    using QuantConnect.Algorithm.Framework.Execution;
    using QuantConnect.Algorithm.Framework.Risk;
    using QuantConnect.Algorithm.Selection;
    using QuantConnect.Api;
    using QuantConnect.Parameters;
    using QuantConnect.Benchmarks;
    using QuantConnect.Brokerages;
    using QuantConnect.Commands;
    using QuantConnect.Configuration;
    using QuantConnect.Util;
    using QuantConnect.Interfaces;
    using QuantConnect.Algorithm;
    using QuantConnect.Indicators;
    using QuantConnect.Data;
    using QuantConnect.Data.Auxiliary;
    using QuantConnect.Data.Consolidators;
    using QuantConnect.Data.Custom;
    using QuantConnect.Data.Custom.IconicTypes;
    using QuantConnect.DataSource;
    using QuantConnect.Data.Fundamental;
    using QuantConnect.Data.Market;
    using QuantConnect.Data.Shortable;
    using QuantConnect.Data.UniverseSelection;
    using QuantConnect.Notifications;
    using QuantConnect.Orders;
    using QuantConnect.Orders.Fees;
    using QuantConnect.Orders.Fills;
    using QuantConnect.Orders.OptionExercise;
    using QuantConnect.Orders.Slippage;
    using QuantConnect.Orders.TimeInForces;
    using QuantConnect.Python;
    using QuantConnect.Scheduling;
    using QuantConnect.Securities;
    using QuantConnect.Securities.Equity;
    using QuantConnect.Securities.Future;
    using QuantConnect.Securities.Option;
    using QuantConnect.Securities.Positions;
    using QuantConnect.Securities.Forex;
    using QuantConnect.Securities.Crypto;
    using QuantConnect.Securities.CryptoFuture;
    using QuantConnect.Securities.IndexOption;
    using QuantConnect.Securities.Interfaces;
    using QuantConnect.Securities.Volatility;
    using QuantConnect.Storage;
    using QuantConnect.Statistics;
    using QCAlgorithmFramework = QuantConnect.Algorithm.QCAlgorithm;
    using QCAlgorithmFrameworkBridge = QuantConnect.Algorithm.QCAlgorithm;
    using Calendar = QuantConnect.Data.Consolidators.Calendar;
#endregion

public class BrainMLRankingDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Seed the price of each asset with its last known price to avoid trading errors.
        Settings.SeedInitialPrices = true;
        // We cherry picked 5 largest stocks, high trading volume provides better information and credibility for ML ranking.
        var tickers = new string[] {"AAPL", "TSLA", "MSFT", "F", "KO"};
        foreach (var ticker in tickers)
        {
            // Requesting data to get 2 days estimated relative ranking.
            var equity = AddEquity(ticker, Resolution.Daily);
            AddData<BrainStockRanking2Day>(equity);
        }
    }

    public override void OnData(Slice slice)
    {
        // Collect rankings for all symbols for ranking them.
        var points = slice.Get<BrainStockRanking2Day>();
        if (points == null)
        {
            return;
        }
        var sumOfRanks = points.Values.Select(x => Math.Abs(x.Rank)).Sum() * 0.9m;
        if (sumOfRanks == 0) return;

        // Rank each symbol's Brain ML ranking relative to the other symbols for positional sizing.
        points.Values.DoForEach(x => Plot("Rank", x.Symbol.Underlying.Value, x.Rank));

        // Place orders, the better the rank, the higher the estimated return and hence weight.
        var targets = points.Values.Select(x => new PortfolioTarget(x.Symbol.Underlying, x.Rank / sumOfRanks)).ToList();
        SetHoldings(targets);
    }
}
