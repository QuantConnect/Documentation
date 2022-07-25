<?php

$getCommonErrorsText = function($isWritingAlgorithms)
{
    $pyVarName = $isWritingAlgorithms ? "self": "qb";
    $pyFuncVariables = $isWritingAlgorithms ? "self, " : "";
    $pyPrintFunction = $isWritingAlgorithms ? "self.Log" : "print";
    $envName = $isWritingAlgorithms ? "algorithms" : "the Research Environment";
    
    if ($isWritingAlgorithms)
    {
        echo "
<p>Errors can occur when you request historical data.</p>    
<h4>Empty Data Errors</h4>      
";
    }
    
    echo "
<p class='python'>If the history request returns an empty DataFrame and you try to slice it, it throws an exception. To avoid issues, check if the DataFrame contains data before slicing it.</p>
<div class='python section-example-container'>
<pre class='python'>df = {$pyVarName}.History(symbol, 10).close    # raises exception if the request is empty

def GetSafeHistoryCloses({$pyFuncVariables}symbols):
    if not symbols:
        {$pyPrintFunction}(f'No symbols')
        return  False, None
    df = {$pyVarName}.History(symbols, 100, Resolution.Daily)
    if df.empty:
        {$pyPrintFunction}(f'Empy history for {symbols}')
        return  False, None
     return True, df.close.unstack(0)</pre>
</div>

<p>If you run {$envName} on your local machine and history requests return no data, check if your <span class='private-file-name'>data</span> directory contains the data you request. To download datasets, see <a href='/docs/v2/our-platform/datasets/licensing#04-Download'>Download</a>.</p>
";
    
    if ($isWritingAlgorithms)
    {
        echo "<h4>Numerical Precision Errors</h4>";
        echo file_get_contents(DOCS_RESOURCES."/datasets/equity-common-errors.html");
    }
}

?>
