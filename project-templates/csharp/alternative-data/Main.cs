using System.Linq;
using QuantConnect;
using QuantConnect.Algorithm;
using QuantConnect.Data;
using QuantConnect.DataSource;
using QuantConnect.Securities.Equity;

public class BrainCompanyFilingNLPDataAlgorithm : QCAlgorithm
{
    private Equity _equity;
    private Symbol _filingData;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Seed the price of each asset with its last known price to avoid trading errors.
        Settings.SeedInitialPrices = true;
        // Request company filing data to obtain sentiment scores from company reports.
        // Combine sentiment with fundamental context, past performance, and forward-looking provisions.
        _equity = AddEquity("AAPL", Resolution.Daily);
        _filingData = AddData<BrainCompanyFilingLanguageMetrics10K>(_equity.Symbol).Symbol;
        // Historical data
        var history = History<BrainCompanyFilingLanguageMetrics10K>(_filingData, 365, Resolution.Daily);
        Debug($"We got {history.Count()} items from our history request for {_filingData}");
    }

    public override void OnData(Slice slice)
    {
        // Trade based on the updated report sentiment.
        if (slice.ContainsKey(_filingData))
        {
            var sentiment = slice[_filingData].ReportSentiment.Sentiment;
            // Buy when sentiment is positive to express the expected positive return projection.
            SetHoldings(_equity.Symbol, sentiment > 0 ? 1 : 0);
        }
    }
}
