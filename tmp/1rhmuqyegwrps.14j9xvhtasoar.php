<html>

<head>
    <link rel="stylesheet" href="/bootstrap.min.css" />
    <link rel="stylesheet" href="/admin.css" />
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Login</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="alert alert-danger <?php if (!$hasError): ?>d-none<?php endif; ?>" role="alert">
                    Benutzername oder Passwort ungültig
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Benutzername</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Anmelden</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>