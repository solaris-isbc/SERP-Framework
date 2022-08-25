<h1><?= ($projectTitle) ?></h1>
<h4><?= ($author) ?></h4>
<p><?= ($taskDescription) ?></p>
<p> <?= ($agreementText) ?> </p>
<form method="GET" action="/systems/<?= ($systemName) ?>">
    <button type="submit">weiter</button>
</form>