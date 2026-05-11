using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainCompanyFilingLanguageMetricsUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            UniverseSettings.Resolution = Resolution.Daily;
            // Add a universe of US Equities with positive sentiment in their latest SEC filings.
            _universe = AddUniverse<BrainCompanyFilingLanguageMetricsUniverseAll>(data =>
            {
                // Filter for assets with positive sentiment in both the report and MD&A sections.
                return from d in data.OfType<BrainCompanyFilingLanguageMetricsUniverseAll>()
                       where d.ReportSentiment != null
                          && d.ReportSentiment.Sentiment.HasValue
                          && d.ReportSentiment.Sentiment > 0m
                          && d.ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations != null
                          && d.ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations.Sentiment.HasValue
                          && d.ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations.Sentiment > 0m
                       select d.Symbol;
            });
            // Schedule daily rebalancing at 9:00 AM before market open.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Where(symbol => Securities[symbol].Price > 0)
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
