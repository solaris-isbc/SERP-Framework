<?php if ($scope=='serp'): ?>
    <?php echo $this->render('views/serp_header.htm',NULL,get_defined_vars(),0); ?>
    <?php foreach (($snippets->getSnippets()?:[]) as $snippet): ?>
        <?php echo $this->render($templatePath,NULL,get_defined_vars(),0); ?>
    <?php endforeach; ?>
    <?php echo $this->render('views/serp_footer.htm',NULL,get_defined_vars(),0); ?>
<?php endif; ?>
<?php if ($scope=='questionnaire'): ?>
    <?php if ($questionnaire->getName() != null): ?>
        <h1><?= ($questionnaire->getName()) ?></h1>
    <?php endif; ?>
    <?php if ($questionnaire->getDescription() != null): ?>
        <p><?= ($questionnaire->getDescription()) ?></p>
    <?php endif; ?>
    <?php foreach (($questionnaire->getQuestions()?:[]) as $question): ?>
        <?php echo $this->render('views/questions/question.htm',NULL,get_defined_vars(),0); ?>
    <?php endforeach; ?>
<?php endif; ?>