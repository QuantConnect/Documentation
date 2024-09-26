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
    <pre class="python"># Coming soon</pre>
</div>
