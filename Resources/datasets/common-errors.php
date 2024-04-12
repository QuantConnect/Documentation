<? if ($writingAlgorithms) { ?>
<p>Errors can occur when you request historical data.</p>    
<h4>Empty Data Errors</h4>      
<? } ?> 

<p class='python'>If the history request returns an empty DataFrame and you try to slice it, it throws an exception. To avoid issues, check if the DataFrame contains data before slicing it.</p>
<div class='python section-example-container'>
<pre class='python'>df = <?=$writingAlgorithms ? "self": "qb"?>.history(symbol, 10).close    # raises exception if the request is empty

def get_safe_history_closes(<?=$writingAlgorithms ? "self, " : "" ?>symbols):
    if not symbols:
        <?=$writingAlgorithms ? "self.log" : "print"?>(f'No symbols')
        return  False, None
    df = <?=$writingAlgorithms ? "self": "qb"?>.history(symbols, 100, Resolution.DAILY)
    if df.empty:
        <?=$writingAlgorithms ? "self.log" : "print"?>(f'Empy history for {symbols}')
        return  False, None
     return True, df.close.unstack(0)</pre>
</div>

<p>If you run <?=$writingAlgorithms ? "algorithms" : "the Research Environment" ?> on your local machine and history requests return no data, check if your <span class='public-file-name'>data</span> directory contains the data you request. To download datasets, see <a href='/docs/v2/cloud-platform/datasets/licensing#04-Download'>Download</a>.</p>

<? if ($writingAlgorithms) { ?>
<h4>Numerical Precision Errors</h4>
<? include(DOCS_RESOURCES."/datasets/equity-common-errors.html"); ?>
<? } ?>
