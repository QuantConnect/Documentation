<p>To set the assignment model of an Option, call the <code>SetOptionAssignmentModel</code> method of the <code>Option</code> object in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.Option: # Option type
            security.SetOptionAssignmentModel(DefaultOptionAssignmentModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            (security as Option).SetOptionAssignmentModel(new DefaultOptionAssignmentModel());
        }";
$comment = "the assignment model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
