<p>To set the assignment model of an Option, call the <code class="csharp">SetOptionAssignmentModel</code><code class="python">set_option_assignment_model</code> method of the <code>Option</code> object inside a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "if security.Type == SecurityType.OPTION: # Option type
            security.set_option_assignment_model(DefaultOptionAssignmentModel())";
$overwriteCodeC = "if (security.Type == SecurityType.Option) // Option type
        {
            (security as Option).SetOptionAssignmentModel(new DefaultOptionAssignmentModel());
        }";
$comment = "the assignment model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
