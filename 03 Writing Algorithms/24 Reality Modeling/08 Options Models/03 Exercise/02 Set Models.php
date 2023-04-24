<p>To set the exercise model of an Option, call the <code>SetOptionExerciseModel</code> method of the <code>Option</code> object.</p>

<p>If you have access to the <code>Option</code> object when you subscribe to the Option universe or contract, you can set the exercise model immediately after you create the subscription.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
var option = AddOption("SPY");
option.SetOptionExerciseModel(new DefaultExerciseModel());</pre>
    <pre class="python"># In Initialize
option = AddOption("SPY")
option.SetOptionExerciseModel(DefaultExerciseModel())</pre>
</div>

<p>Otherwise, set the assignment model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.Option: # Option type
            option.SetOptionExerciseModel(DefaultExerciseModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            option.SetOptionExerciseModel(new DefaultExerciseModel());
        }";
$comment = "the exercise model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
