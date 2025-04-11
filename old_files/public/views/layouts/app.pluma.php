<?php

use Core\View; ?>
<!DOCTYPE html>
<html lang="en">
<?= include_partial('head'); ?>

<body class="bg-slate-100">

  <main>
    <?php View::section('content'); ?>
  </main>
</body>

</html>