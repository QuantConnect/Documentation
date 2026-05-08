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

using Newtonsoft.Json;

public class CustomDataBitstampAlgorithm : QCAlgorithm
{
    private Security _btc;

    public override void Initialize()
    {
        SetStartDate(2020, 9, 1);
        SetEndDate(2020, 12, 31);
        // Define the symbol and "type" of our custom data.
        _btc = AddData<Bitstamp>("BTC", Resolution.Daily);
        // Get some historical data.
        var history = History<Bitstamp>(_btc, 200, Resolution.Daily);
    }

    // Get the data of the current day.
    public void OnData(Bitstamp data)
    {
        Plot(_btc.Symbol.Value, "Price", data.Close);
    }

    public class Bitstamp : BaseData
    {
        public int Timestamp = 0;
        public decimal Open = 0, High = 0, Low = 0, Close = 0, Bid = 0, Ask = 0, WeightedPrice = 0, VolumeBTC = 0, VolumeUSD = 0;

        public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
        {
            if (isLiveMode)
            {
                return new SubscriptionDataSource("https://www.bitstamp.net/api/ticker/", SubscriptionTransportMedium.Rest);
            }
            var source = "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/bitstampusd.csv";
            return new SubscriptionDataSource(source, SubscriptionTransportMedium.RemoteFile);
        }

        public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
        {
            if (string.IsNullOrWhiteSpace(line.Trim()))
            {
                return null;
            }
            var coin = new Bitstamp() {Symbol = config.Symbol};
            // In live trading, parse the JSON file.
            if (isLiveMode)
            {
                // Example Line Format:
                // {"high": "441.00", "last": "421.86", "timestamp": "1411606877", "bid": "421.96", "vwap": "428.58", "volume": "14120.40683975", "low": "418.83", "ask": "421.99"}.
                coin = JsonConvert.DeserializeObject<Bitstamp>(line);
                coin.EndTime = DateTime.UtcNow.ConvertFromUtc(config.ExchangeTimeZone);
                coin.Time = coin.EndTime.AddDays(-1);
                coin.Value = coin.Close;
                return coin;
            }

            // In backtests, parse the CSV file.
            // Example Line Format:
            // Date      Open   High    Low     Close   Volume (BTC)    Volume (Currency)   Weighted Price.
            // 2011-09-13 5.8    6.0     5.65    5.97    58.37138238,    346.0973893944      5.929230648356.
            if (!char.IsDigit(line[0]))
            {
                return null;
            }
            var data = line.Split(',');
            // If value is zero, return null.
            coin.Value = data[4].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            if (coin.Value == 0)
            {
                return null;
            }
            coin.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
            coin.EndTime = coin.Time.AddDays(1);
            coin.Open = data[1].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.High = data[2].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.Low = data[3].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.VolumeBTC = data[5].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.VolumeUSD = data[6].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.WeightedPrice = data[7].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
            coin.Close = coin.Value;
            return coin;
        }
    }
}
