<?php
	echo file_get_contents(DOCS_RESOURCES."/introduce-optimization.html");
?>

<p>Parameters are project variables that your algorithm uses to define the value of internal variables like indicator arguments or the length of lookback windows. Parameters are stored outside of your algorithm code, but the values of the parameters are injected into your algorithm when you <a href='/docs/v2/our-platform/tutorials/optimization/running-optimizations'>launch an optimization job</a>. The optimizer adjusts the value of your <a href='/docs/v2/our-platform/user-guides/projects/structure#06-Parameters'>project parameters</a> across a range and step size that you define to minimize or maximize an objective function. To optimize some parameters, <a href='/docs/v2/our-platform/tutorials/projects/managing-projects#09-Edit-Algorithm-Parameters'>add some parameters to your project</a>.</p>
