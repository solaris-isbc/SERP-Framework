<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/resources/<?= ($system->getFolder()) ?>/global.css" />
</head>

<body>

    <?php if ($scope=='serp'): ?>
        <div id="serp_preview">

            <?php echo $this->render('views/serp_header.htm',NULL,get_defined_vars(),0); ?>
            <form method="POST">
                <input type="hidden" name="pagetype" value="SERP" />
                <input type="hidden" name="pageId" value="<?= ($snippets->getId()) ?>" />
                <?php foreach (($snippets->getSnippets()?:[]) as $snippet): ?>
                    <?php echo $this->render($templatePath,NULL,get_defined_vars(),0); ?>
                    <?php echo $this->render('views/questions/question.htm',NULL,['answerType'=>$snippets->getAnswerType() ,'question'=>$snippets->getQuestion() ,'id'=>$snippet->getId() ,'answers'=>$snippets->getAnswers() ,'isRequired'=>true]+get_defined_vars(),0); ?>

                <?php endforeach; ?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="submit">Weiter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <?php if ($scope=='questionnaire'): ?>
        <div class="container">
            <form method="POST">
                <input type="hidden" name="pagetype" value="QUESTIONNAIRE" />
                <input type="hidden" name="pageid" value="<?= ($questionnaire->getId()) ?>" />

                <?php if ($questionnaire->getName() != null): ?>
                    <div class="row">
                        <div class="col">
                            <h1><?= ($questionnaire->getName()) ?></h1>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($questionnaire->getDescription() != null): ?>
                    <div class="row">
                        <div class="col">
                            <p><?= ($questionnaire->getDescription()) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach (($questionnaire->getQuestions()?:[]) as $question): ?>
                    <div class="row">
                        <div class="col">

                            <?php echo $this->render('views/questions/question.htm',NULL,['answerType'=>$question->getAnswerType() ,'question'=>$question->getQuestion() ,'id'=>$question->getId() ,'answers'=>$question->getAnswers() ,'isRequired'=>$question->isRequired()]+get_defined_vars(),0); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="submit">Weiter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>