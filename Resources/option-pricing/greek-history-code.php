<div class="section-example-container">
<pre class="csharp">public class OptionHistoryAlgorithm : QCAlgorithm
{       
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        var option = <?=$addOptionC?>

        var history = History&lt;OptionUniverse&gt;(option.Symbol, 5);
        foreach (var chain in history)
        {
            var endTime = chain.EndTime;
            var filteredContracts = chain.Data
                .Select(contract => contract as OptionUniverse)
                .Where(contract => contract.Greeks.Delta > 0.3m);
            foreach (var contract in filteredContracts)
            {
                var price = contract.Price;
                var iv = contract.ImpliedVolatility;
            }
        }
    }
}</pre>
    <pre class="python">class OptionHistoryAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2020, 1, 1)
        option = <?=$addOptionPy?>

        # DataFrame example:
        history_df = self.history(option.symbol, 5, flatten=True)
        # OptionUniverse objects example:
        history = self.history[OptionUniverse](option.symbol, 5)
        for chain in history:
            end_time = chain.end_time
            filtered_contracts = [c for c in chain if c.greeks.delta > 0.3]
            for contract in filtered_contracts:
                symbol = contract.symbol
                expiry = contract.id.date
                strike = contract.id.strike_price
                price = contract.close
                iv = contract.implied_volatility</pre>
</div>

<!-- FOP code when the data is available:
  
public class OptionHistoryAlgorithm : QCAlgorithm
{       
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        // Subscribe to the underlying Futures contracts.
        var future = AddFuture(
            Futures.Indices.SP500EMini,
            dataMappingMode: DataMappingMode.OpenInterest,
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            contractDepthOffset: 0
        );
        future.SetFilter(futureFilterUniverse => futureFilterUniverse.FrontMonth());
        // Subscribe to the Future Options contracts.
        AddFutureOption(future.Symbol);
        // Get the Futures contract that's currently in the universe.
        var futuresContract = FutureChainProvider
            .GetFutureContractList(future.Symbol, Time)
            .OrderBy(symbol => symbol.ID.Date)
            .First();
        // Get the history of Option contracts for the selected Futures contract.
        var history = History&lt;OptionUniverse&gt;(futuresContract, 5);
        foreach (var chain in history)
        {
            var endTime = chain.EndTime;
            var filteredContracts = chain.Data
                .Select(contract => contract as OptionUniverse)
                .Where(contract => contract.Greeks.Delta > 0.3m);
            foreach (var contract in filteredContracts)
            {
                var price = contract.Price;
                var iv = contract.ImpliedVolatility;
            }
        }
    }
}
-->
