
<div class="question">
        <?php if ($answerType == serpframework\config\Question::ANSWER_TYPE_TEXT): ?>
                <?php echo $this->render('views/questions/answer_type_text.htm',NULL,get_defined_vars(),0); ?>
        <?php endif; ?>
        <?php if ($answerType == serpframework\config\Question::ANSWER_TYPE_CHECKBOX): ?>
                <?php echo $this->render('views/questions/answer_type_checkbox.htm',NULL,get_defined_vars(),0); ?>
        <?php endif; ?>
        <?php if ($answerType == serpframework\config\Question::ANSWER_TYPE_RADIO): ?>
                <?php echo $this->render('views/questions/answer_type_radio.htm',NULL,get_defined_vars(),0); ?>
        <?php endif; ?>
        <?php if ($answerType == serpframework\config\Question::ANSWER_TYPE_LIKERT): ?>
                <?php echo $this->render('views/questions/answer_type_likert.htm',NULL,get_defined_vars(),0); ?>
        <?php endif; ?>
</div>