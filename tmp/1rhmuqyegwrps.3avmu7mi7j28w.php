<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/admin.css" />
    <script src="/bootstrap.min.js" type="text/javascript"></script>
</head>

<body>
    <?php echo $this->render('views/admin/header.htm',NULL,get_defined_vars(),0); ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3><?= ($system->getMember('name')) ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item"><a href="/showSystem/<?= ($system->getIdentifier()) ?>">Vorschau</a></li>
                    <li class="list-group-item"><a href="/systemConfiguration/<?= ($system->getIdentifier()) ?>">Konfiguration</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
<script src="/global.js" type="text/javascript"></script>

</html>