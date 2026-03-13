<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'StageLink') ?></title>
    <link rel="stylesheet" href="/static/assets/style.css">
</head>
<body>
    <?php require ROOT . '/app/Views/partials/header.php'; ?>
    <?php require ROOT . '/app/Views/partials/navbar.php'; ?>
    <main><?= $content ?></main>
    <?php require ROOT . '/app/Views/partials/footer.php'; ?>
</body>
</html>
