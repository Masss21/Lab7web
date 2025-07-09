<!-- app/Views/template/auth_layout.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Login' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?> @5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>