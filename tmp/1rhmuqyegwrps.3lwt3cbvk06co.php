<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/global.css" />
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
                    <div id="serp_result" class="serp-result <?php if ($system->getMember('hasDocuments')): ?>hasDocuments
    <?php endif; ?>"
    data-id="<?= ($snippet->getId()) ?>"
    <?php if ($system->getMember('hasDocuments')): ?>
        data-document="/resources/<?= ($system->getFolder()) ?>/documents/<?= ($snippet->getId()) ?>/<?= ($snippet->getId()) ?>.html"
    <?php endif; ?>
    >
    <?php echo $this->render($templatePath,NULL,get_defined_vars(),0); ?>
    </div>
    <?php echo $this->render('views/questions/question.htm',NULL,['answerType'=>$snippets->getAnswerType() ,'question'=>$snippets->getQuestion() ,'id'=>$snippet->getId() ,'answers'=>$snippets->getAnswers() ,'isRequired'=>true]+get_defined_vars(),0); ?>

    <?php endforeach; ?>
    <?php if ($environment == 'live'): ?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="submit">Weiter</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </form>
    </div>
    <?php endif; ?>
    <?php if ($scope=='questionnaire'): ?>
        <div class="container">
            <form method="POST">
                <input type="hidden" name="pagetype" value="QUESTIONNAIRE" />
                <input type="hidden" name="pageId" value="<?= ($questionnaire->getId()) ?>" />

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
    <div class="hidden" id="previewContainer">
        <div class="previewContainerHeader">
            <button class="btn btn-primary closePreviewButton">
                Dokument schlie&szlig;en
            </button>
        </div>
        <iframe id="documentPreview" src="" scrolling="no"></iframe>
        <div class="previewContainerFooter">
            <button class="btn btn-primary closePreviewButton">
                Dokument schlie&szlig;en
            </button>
        </div>
    </div>
    <script src="/global.js" type="text/javascript"></script>
</body>