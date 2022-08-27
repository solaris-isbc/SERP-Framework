<html>
<head>
    <link rel="stylesheet" href="/resources/<?= ($system->getFolder()) ?>/global.css" />     
</head>

<body>

<?php if ($scope=='serp'): ?>
    <div id="serp_preview">

    <?php echo $this->render('views/serp_header.htm',NULL,get_defined_vars(),0); ?>
    <form method="POST">
        <?php foreach (($snippets->getSnippets()?:[]) as $snippet): ?>
            <?php echo $this->render($templatePath,NULL,get_defined_vars(),0); ?>
            <?php echo $this->render('views/questions/question.htm',NULL,['answerType'=>$snippets->getAnswerType() ,'question'=>$snippets->getQuestion() ,'id'=>$snippet->getId() ,'answers'=>$snippets->getAnswers() ,'isRequired'=>true]+get_defined_vars(),0); ?>

        <?php endforeach; ?>
        <div class="">
            <button type="submit" name="submit">Weiter</button>
        </div>
    </form>
    </div>
<?php endif; ?>
<?php if ($scope=='questionnaire'): ?>
    <form method="POST">
        <?php if ($questionnaire->getName() != null): ?>
            <h1><?= ($questionnaire->getName()) ?></h1>
        <?php endif; ?>
        <?php if ($questionnaire->getDescription() != null): ?>
            <p><?= ($questionnaire->getDescription()) ?></p>
        <?php endif; ?>
        <?php foreach (($questionnaire->getQuestions()?:[]) as $question): ?>
            <?php echo $this->render('views/questions/question.htm',NULL,['answerType'=>$question->getAnswerType() ,'question'=>$question->getQuestion() ,'id'=>$question->getId() ,'answers'=>$question->getAnswers() ,'isRequired'=>$question->isRequired()]+get_defined_vars(),0); ?>
        <?php endforeach; ?>
        <div class="">
            <button type="submit" name="submit">Weiter</button>
        </div>
    </form>
<?php endif; ?>