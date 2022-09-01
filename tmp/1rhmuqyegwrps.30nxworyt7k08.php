<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/admin.css" />
    <script src="/bootstrap.min.js" type="text/javascript"></script>

    <script src="/libs/codemirror/codemirror.js"></script>
    <link rel="stylesheet" href="/libs/codemirror/codemirror.css">
    <script src="/libs/codemirror/mode/css/css.js"></script>
    <script src="/libs/codemirror/mode/javascript/javascript.js"></script>

    <script src="/libs/beautify_js/beautify.js"></script>
    <script src="/libs/beautify_js/beautify-css.js"></script>
    <script src="/libs/beautify_js/beautify-html.js"></script>

</head>

<body>
    <?php echo $this->render('views/admin/header.htm',NULL,get_defined_vars(),0); ?>
    <div class="container">

        <div class="row mt-3">
            <div class="col text-center">
                <h3>Globale Konfiguration</h3>
            </div>
        </div>
        <form method="POST" action="/administration">
            <?php foreach (($config->getSections()?:[]) as $sectionName=>$section): ?>
                <div class="row mt-3">
                    <div class="col">
                        <h4><?= ($sectionName) ?></h4>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <textarea id="cm_<?= ($sectionName) ?>" name="sections[<?= ($sectionName) ?>]"
                            class="codemirror"><?= (json_encode($section)) ?></textarea>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row mt-3">
                <div class="col">
                    <h4>SERP-Header</h4>
                    <div class="alert alert-info">
                        <?php $bleft='{'; ?>
                        <?php $bright='}'; ?>
                        Die aktulle Query für die SERP kann über die Variable <?= ($bleft) ?><?= ($bleft) ?> @snippets->getQuery() <?= ($bright) ?><?= ($bright) ?>  eingebunden werden.
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <textarea id="cm_header" name="header" data-mode="text/html"
                                class="codemirror"><?= ($header) ?></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                   <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </div>
        </form>
    </div>
    <script src="/global.js"></script>
</body>

</html>