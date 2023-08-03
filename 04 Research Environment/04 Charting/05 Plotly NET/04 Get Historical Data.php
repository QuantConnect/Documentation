<p><a href="/docs/v2/research-environment/datasets/us-equity#04-Get-Historical-Data">Get some historical market data</a> to produce the plots. For example, to get data for a bank sector ETF and some banking companies over 2021, run:</p>

<div class="section-example-container">
    <pre class="csharp">var qb = new QuantBook();
var tickers = new[] 
{
    "XLF",  // Financial Select Sector SPDR Fund
    "COF",  // Capital One Financial Corporation
    "GS",   // Goldman Sachs Group, Inc.
    "JPM",  // J P Morgan Chase &amp; Co
    "WFC"   // Wells Fargo &amp; Company   
};
var symbols = tickers.Select(ticker => qb.AddEquity(ticker, Resolution.Daily).Symbol);
var history = qb.History(symbols, new DateTime(2021, 1, 1), new DateTime(2022, 1, 1));</pre>
</div>
