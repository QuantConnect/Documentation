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
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of US Equities with positive sentiment in their latest SEC filings.
            _universe = AddUniverse<BrainCompanyFilingLanguageMetricsUniverseAll>(data =>
            {
                // Keep names with positive sentiment in both the report and MD&A sections.
                return from d in data.OfType<BrainCompanyFilingLanguageMetricsUniverseAll>()
                       where d.ReportSentiment.Sentiment > 0m
                          && d.ManagementDiscussionAnalyasisOfFinancialConditionAndResultsOfOperations.Sentiment > 0m
                       select d.Symbol;
            });

            // Rebalance shortly after the open so today's universe is locked in.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
