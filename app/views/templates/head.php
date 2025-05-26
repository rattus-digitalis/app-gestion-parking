<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($title ?? 'Zenpark') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSS principal -->
    <link rel="stylesheet" href="/assets/css/style.css" />

    <!-- CSS spécifique par page -->
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($page_css) ?>" />
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon" />
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1><a href="/?page=home" class="logo">Zenpark</a></h1>
        </div>
    </header>
