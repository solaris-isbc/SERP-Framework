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

        <div class="row">
            <div class="col text-center">
                <h3>Globale Konfiguration</h3>
            </div>
        </div>
        <form method="POST" action="/administration">
            <?php foreach (($config->getSections()?:[]) as $sectionName=>$section): ?>
                <div class="row">
                    <div class="col">
                        <h4><?= ($sectionName) ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <textarea id="cm_<?= ($sectionName) ?>" name="sections[<?= ($sectionName) ?>]"
                            class="codemirror"><?= (json_encode($section)) ?></textarea>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row">
                <div class="col">
                   <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </div>
        </form>
    </div>
    <script src="/global.js"></script>
</body>

</html>