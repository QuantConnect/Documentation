<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">private Symbol _spy;
private List&lt;Symbol&gt; _<?=strtolower($typeName)?>s = new();

public override void Initialize()
{
    // Subscribe to the underlying
    _spy = AddEquity("SPY", dataNormalizationMode=DataNormalizationMode.Raw).Symbol;
    
    // Set up a scheduled event to select contract and create Greeks indicator daily before market open
    Schedule.On(
        DateRules.EveryDay(_spy),
        TimeRules.At(9, 0),
        UpdateContractsAndGreeks
    );
}

private void UpdateContractsAndGreeks()
{
    // Get all tradable option contracts
    var contractList = OptionChainProvider.GetOptionContractList(_spy, Time).ToList();
    // You can do further filtering here

    // Iterate all expiries
    foreach (var expiry in contractList.Select(x =&gt; x.ID.Date).Distinct())
    {
        var contractsByExpiry = contractList.Where(x =&gt; x.ID.Date == expiry).ToList();

        // Iterate all strike prices among the contracts of the same expiry
        foreach (var strike in contractsByExpiry.Select(x =&gt; x.ID.StrikePrice).Distinct())
        {
            var contractsByStrike = contractsByExpiry.Where(x =&gt; x.ID.StrikePrice == strike).ToList();

            // Get the call and put respectively
            var call = contractsByStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Call);
            var put = contractsByStrike.SingleOrDefault(x =&gt; x.ID.OptionRight == OptionRight.Put);
            // Skip if either call or put not exist
            if (call == null || put == null) continue;

            // Create subscriptions to both contract
            call = AddOptionContract(call).Symbol;
            put = AddOptionContract(put).Symbol;

            // Create the automatic-updating <?=$typeName?> indicator
            var call<?=$typeName?> = <?=$helperMethod?>(call, put);
            var put<?=$typeName?> = <?=$helperMethod?>(put, call);
            // Add to list of indicator
            _<?=strtolower($typeName)?>s.Add(call<?=$typeName?>);
            _<?=strtolower($typeName)?>s.Add(put<?=$typeName?>);
        }
    }
}

public override void OnData(Slice slice)
{
    // Access the <?=$typeName?> indicator of each contract
    foreach (var <?=strtolower($typeName)?> in _<?=strtolower($typeName)?>s)
    {
        Log($"{<?=strtolower($typeName)?>.OptionSymbol}::{<?=strtolower($typeName)?>.Current.EndTime}::{<?=strtolower($typeName)?>.Current.Value}");
    }
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # List to hold Greeks indicators
    self.<?=strtolower($typeName)?>s = []

    # Subscribe to the underlying
    self.spy = self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol

    # Set up a scheduled event to select contract and create Greeks indicator daily before market open
    self.schedule.on(
        self.date_rules.every_day(self.spy),
        self.time_rules.at(9, 0),
        self.update_contracts_and_greeks
    )

def update_contracts_and_greeks(self) -&gt; None:
    # Get all tradable option contracts
    contract_list = self.option_chain_provider.get_option_contract_list(self.spy, self.time)
    # You can do further filtering here

    # Iterate all expiries
    for expiry in set(x.id.date for x in contract_list):
        contract_by_expiry = [x for x in contract_list if x.id.date == expiry]
        
        # Iterate all strike prices among the contracts of the same expiry
        for strike in set(x.id.strike_price for x in contract_by_expiry):
            contract_by_strike = [x for x in contract_by_expiry if x.id.strike_price == strike]
        
            # Get the call and put respectively
            call = next(filter(lambda x: x.id.option_right == OptionRight.CALL, contract_by_strike))
            put = next(filter(lambda x: x.id.option_right == OptionRight.PUT, contract_by_strike))
            # Skip if either call or put not exist
            if not call or not put:
                continue
            
            # Create subscriptions to both contract
            call = self.add_option_contract(call).symbol
            put = self.add_option_contract(put).symbol
        
            # Create the automatic-updating <?=$typeName?> indicator
            call_<?=strtolower($typeName)?> = self.<?=strtolower($helperMethod)?>(call, put)
            put_<?=strtolower($typeName)?> = self.<?=strtolower($helperMethod)?>(put, call)
            # Add to list of indicator
            self.<?=strtolower($typeName)?>s.extend([call_<?=strtolower($typeName)?>, put_<?=strtolower($typeName)?>])
        
def on_data(self, slice: Slice) -&gt; None:
    # Access the <?=$typeName?> indicator of each contract
    for <?=strtolower($typeName)?> in self.<?=strtolower($typeName)?>s:
        self.log(f"{<?=strtolower($typeName)?>.option_symbol}::{<?=strtolower($typeName)?>.current.end_time}::{<?=strtolower($typeName)?>.current.value}")</pre>
</div>
