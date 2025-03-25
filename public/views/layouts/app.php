<?php use Core\View; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?= View::get('title', 'Pluma Framework') ?></title>
</head>
<body>
  <h1>Layout ativo</h1>

  <main>
    <?php View::section('content'); ?>
  </main>
</body>
</html>
