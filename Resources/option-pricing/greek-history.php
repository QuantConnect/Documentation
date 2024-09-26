<p class='python'><i>This feature is coming soon.</i></p>

<div class='csharp'>
  <p>The Greeks and IV values that you get from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a> of the Option universe are the daily, pre-calculated values based on the end of the previous trading day.</p>

  <div class="section-example-container">
    <pre class="csharp">public class OptionHistoryAlgorithm : QCAlgorithm
{       
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        var option = <=$addOptionC?>
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
    <pre class="python"># Coming soon</pre>
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
  
  <p>
    You can't customize the Greeks and IV values that you get from a history request.
    However, you can create <a href='<?=$indicatorLink?>'>indicators</a> to customize how the Greeks and IV are calculated for the contracts already in your universe.
  </p>
  
</div>
