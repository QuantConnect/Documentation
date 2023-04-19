<p>To set the assignment model of an Option, call the <code>SetOptionAssignmentModel</code> method of the <code>Option</code> object.</p>

<p>If you have access to the <code>Option</code> object when you subscribe to the Option universe or contract, you can set the assignment model immediately after you create the subscription.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var option = AddOption("SPY");
option.SetOptionAssignmentModel(new DefaultOptionAssignmentModel());</pre>
    <pre class="python"># In Initialize
option = AddOption("SPY")
option.SetOptionAssignmentModel(DefaultOptionAssignmentModel())</pre>
</div>

<p>Otherwise, set the assignment model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.Option: # Option type
            option.SetOptionAssignmentModel(DefaultOptionAssignmentModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            option.SetOptionAssignmentModel(new DefaultOptionAssignmentModel());
        }";
$comment = "the assignment model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
