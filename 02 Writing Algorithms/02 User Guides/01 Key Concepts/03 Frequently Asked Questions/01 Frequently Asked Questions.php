<?php

/**
 * @var array<{title: string, questions: [id: int, question: string, answer: string, key: string, weight: int]}>
 * $questionsCatalog
 */

?>
<meta name="tag" content="using quantconnect"/>
<div class="col-xs-12 faq-content">
    <?php

    foreach ($questionsCatalog as $catalog) {

        $questions = $catalog['questions'];

        ?>
        <div class="row questions-header">
            <div class="">
                <h2><?= $catalog['title'] ?></h2>
            </div>
        </div>
        <div class="row questions-row">
            <?php

            foreach ($questions as $question) {
                ?>
                <div class="col-xs-12 question" onclick="$(this).toggleClass('open')">
                    <h3><?= $question['question'] ?></h3>
                    <div><?= $question['answer'] ?></div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
