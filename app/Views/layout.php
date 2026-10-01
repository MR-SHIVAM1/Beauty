<?php use App\Core\View; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Honey Beauty | Premium Makeup & Skincare') ?></title>
    <meta name="description" content="Honey Beauty — premium makeup and skincare, delivered.">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍯</text></svg>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php View::partial('partials/header', get_defined_vars()); ?>

<main>
    <?= $content ?>
</main>

<?php View::partial('partials/footer', get_defined_vars()); ?>

<script src="/assets/js/app.js"></script>
</body>
</html>
