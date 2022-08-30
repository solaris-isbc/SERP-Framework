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
                <h3>Datenexport</h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item"><a href="/export/answers">Hauptdaten</a></li>
                    <li class="list-group-item"><a href="/export/dataPoints">Zusatzdaten</a></li>
                </ul>
            </div>
        </div>
    </div>

</body>

</html>