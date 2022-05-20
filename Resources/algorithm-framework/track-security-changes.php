<?php
$getTrackSecurityChangesText = function($modelName, $modelClassName, $callsBaseClass) {

    $result = "<p>The <a href='/docs/v2/writing-algorithms/algorithm-framework/universe-selection/key-concepts'>Universe Selection model</a> may select a dynamic universe of assets, so you should not assume a fixed set of assets in the Execution model. When the Universe Selection model adds and removes assets from the universe, it triggers an <code>OnSecuritiesChanged</code> event. In the <code>OnSecuritiesChanged</code> event handler, you can initialize the security-specific state or load any history required for your {$modelName}.</p>";
    $result .= "
<div class=\"section-example-container\">
		<pre class=\"csharp\">class My{$modelClassName} : {$modelClassName}
{
    private Dictionary&lt;symbol, symboldata&gt; _symbolDataBySymbol = new Dictionary&lt;symbol, symboldata&gt;();

    void OnSecuritiesChanged(QCAlgorithmFramework algorithm, SecurityChanges changes)
    {";
  
  if ($callsBaseClass)
  {
      $result .= "
        base.OnSecuritiesChanged(algorithm, changes)";
  }
  
  $result .= "
        foreach (var security in changes.AddedSecurities)
        {               
            _symbolDataBySymbol[security.Symbol] = new SymbolData(security.Symbol);
        }

        foreach (var security in changes.RemovedSecurities)
        {
            if (_symbolDataBySymbol.ContainsKey(security.Symbol))
            {
                _symbolDataBySymbol.Remove(security.Symbol);
            }
        }
    }

    public class SymbolData 
    {
        private Symbol _symbol;

        public SymbolData(Symbol symbol)
        {
            _symbol = symbol;
            // Store and manage Symbol-specific data
        }
    }
}</pre>
		<pre class=\"python\">class My{$modelClassName}($modelClassName):
    symbol_data_by_symbol = {}

    def OnSecuritiesChanged(self, algorithm, changes):";
  
    
  if ($callsBaseClass)
  {
      $result .= "
        super().OnSecuritiesChanged(algorithm, changes)";
  }
  
  $result .= "
        for security in changes.AddedSecurities:
            self.symbol_data_by_symbol[security.Symbol] = SymbolData(security.Symbol)

        for security in changes.RemovedSecurities:
            if security.Symbol in self.symbol_data_by_symbol:
                self.symbol_data_by_symbol.pop(security.Symbol, None)

class SymbolData:
    def __init__(self, symbol):
        self.symbol = symbol
        # Store and manage Symbol-specific data</pre>
	</div>    
";
  
    echo $result;
}
?>
