<p>To set the exercise model of an Option, call the <code>SetOptionExerciseModel</code> method of the <code>Option</code> object inside a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.Option: # Option type
            security.SetOptionExerciseModel(DefaultExerciseModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            (security as Option).SetOptionExerciseModel(new DefaultExerciseModel());
        }";
$comment = "the Option exercise model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
