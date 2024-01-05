<p>To set the assignment model of an Option, in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>, call the <code>SetOptionAssignmentModel</code> method of the <code>Option</code> object.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.Option: # Option type
            security.SetOptionAssignmentModel(DefaultOptionAssignmentModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            security.SetOptionAssignmentModel(new DefaultOptionAssignmentModel());
        }";
$comment = "the assignment model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
