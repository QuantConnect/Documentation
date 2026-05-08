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

public class CustomIndicatorsAlgorithm : QCAlgorithm
{
    private Equity _spy;
    private CustomMoneyFlowIndex _customMfi;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);

        // Request daily SPY data to feed the indicators to generate trade signals and trade.
        _spy = AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw);

        // Create a custom money flow index to generate a trade signal.
        _customMfi = new CustomMoneyFlowIndex(20);

        // Warm up for immediate usage of indicators.
        SetWarmUp(20, Resolution.Daily);
    }

    public override void OnData(Slice slice)
    {
        if (!slice.Bars.TryGetValue(_spy, out var bar))
        {
            return;
        }
        // Update the custom MFI with the updated trade bar to obtain the updated trade signal.
        _customMfi.Update(bar);
        // Don't trade until warm-up is done.
        if (IsWarmingUp)
        {
            return;
        }
        // Buy if the positive money flow is above the negative, which indicates demand is greater than supply, driving up the price.
        if (_customMfi > 50)
        {
            SetHoldings(_spy, percentage: 1d);
        }
        // Sell if the positive money flow is below negative, indicating demand is less than supply, driving down the price.
        else
        {
            SetHoldings(_spy, percentage: -1d);
        }
    }

    public class CustomMoneyFlowIndex : TradeBarIndicator, IIndicatorWarmUpPeriodProvider
    {
        private decimal _previousTypicalPrice;
        private RollingWindow<decimal> _negativeMoneyFlow;
        private RollingWindow<decimal> _positiveMoneyFlow;
        public override bool IsReady => _positiveMoneyFlow.IsReady;
        public int WarmUpPeriod => _positiveMoneyFlow.Size;

        public CustomMoneyFlowIndex(int period) : base("CustomMFI")
        {
            _negativeMoneyFlow = new(period);
            _positiveMoneyFlow = new(period);
            _previousTypicalPrice = 0m;
        }

        protected override decimal ComputeNextValue(TradeBar input)
        {
            // Estimate the money flow by averaging the price multiplied by volume.
            var typicalPrice = (input.High + input.Low + input.Close) / 3;
            var moneyFlow = typicalPrice * input.Volume;

            // We need to avoid double rounding errors.
            _negativeMoneyFlow.Add(typicalPrice < _previousTypicalPrice ? moneyFlow: 0);
            _positiveMoneyFlow.Add(typicalPrice > _previousTypicalPrice ? moneyFlow: 0);
            _previousTypicalPrice = moneyFlow;

            // Add the period money flow to calculate the aggregated money flow.
            var positiveMoneyFlowSum = _positiveMoneyFlow.Sum();
            var totalMoneyFlow = positiveMoneyFlowSum + _negativeMoneyFlow.Sum();

            // Set the value to be the positive money flow ratio.
            return totalMoneyFlow == 0 ? 100m : 100m * positiveMoneyFlowSum / totalMoneyFlow;
        }

        public override void Reset()
        {
            _previousTypicalPrice = 0m;
            _negativeMoneyFlow.Reset();
            _positiveMoneyFlow.Reset();
            base.Reset();
        }
    }
}
