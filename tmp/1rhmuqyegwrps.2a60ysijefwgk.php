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
            <div class="col text-center">
                <h3>Konfiguration <?= ($system->getMember('name')) ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
               
                TODO: read json and display, show js and css in editors
            </div>
        </div>
    </div>

</body>

</html>