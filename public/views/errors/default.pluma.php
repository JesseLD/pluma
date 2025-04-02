<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Error <?= $code ?? 'Error' ?></title>
</head>

<body>
  <h1>Error <?= $code ?? 'Error' ?></h1>
  <p><?= htmlspecialchars($message ?? 'An error occurred') ?></p>
</body>

</html>