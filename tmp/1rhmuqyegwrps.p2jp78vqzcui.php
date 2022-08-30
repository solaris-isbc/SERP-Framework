<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/admin.css" />
    <script src="/bootstrap.min.js" type="text/javascript"></script>
</head>

<body>
    <?php echo $this->render('views/admin/header.htm',NULL,get_defined_vars(),0); ?>
    <div id="previewContainer">
        <input type="hidden" id="serpPreviewAnchor" data-system="/preview/<?= ($system->getIdentifier()) ?>" />
    </div>
    </div>
</body>
<script src="/global.js" type="text/javascript"></script>

</html>