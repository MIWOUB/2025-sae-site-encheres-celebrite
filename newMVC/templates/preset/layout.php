<?php

if (isset($_SESSION['user'])) {
    $loginController = new \LoginController();
    $loginController->checkconnection($_SESSION['user']['DateConnexion']);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? '') ?></title>
    <link rel="stylesheet" href="templates/Style/variables.css">
    <?php if (!empty($style)) : ?>
        <link href="<?= htmlspecialchars($style) ?>" rel="stylesheet">
    <?php endif; ?>

    <?php if (!empty($script)) : ?>
        <script src="<?= htmlspecialchars($script) ?>" defer></script>
    <?php endif; ?>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@400;500;600&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <?= $content ?>
    <?php include 'templates/preset/loginModal.php'; ?>
    <?php include 'templates/preset/signinModal.php'; ?>
    <?php include 'templates/preset/subscribeModal.php'; ?>
</body>

</html>
