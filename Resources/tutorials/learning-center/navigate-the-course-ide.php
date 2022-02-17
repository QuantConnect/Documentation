<?php

$getNavigateCourseIDEText = function($isGettingStarted) {
    echo "<p>Follow these steps to navigate the course IDE:</p>";
    echo "<ol>";
    if (!$isGettingStarted) {
        echo "
              <li><a href='/docs/v2/our-platform/tutorials/learning-center/enrolling-in-courses#03-Enroll-in-Courses'>Enroll in a course</a>.</li>
              <p>The Learning Center environment displays.</p>
              <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/navigate-course-ide.jpg'>
        ";
    }

    echo "
              <li>Read the instructions in the left panel.</li>
              <li>Update the <span class='public-file-name'>main.py</span> file with your answer.</li>
              <li><span class='qualifier'>(Optional)</span> Scroll down to the bottom of the instruction panel and click <span class='button-name'>Show Hint</span> to show a hint.</li>
              <p>A hint displays at the bottom of the instruction panel.</p>
              <li><span class='qualifier'>(Optional)</span> Scroll down to the bottom of the instruction panel and click <span class='button-name'>Solution</span> to show the solution file.</li>
              <p>A <span class='public-file-name'>solution.py</span> file displays.</p>
              <li><span class='qualifier'>(Optional)</span> Click <span class='button-name'>Reset</span> to reset the <span class='public-file-name'>main.py</span> file.</li>
              <li>Click <span class='button-name'>Submit</span> to check your answer.</li>
              <p>The Chart panel displays your backtest results.</p>
              <li>If an error message displays, restart from step 2.</li>
              <li>Click <span class='button-name'>Continue</span>.</li>
          </ol>
	";
}

?>