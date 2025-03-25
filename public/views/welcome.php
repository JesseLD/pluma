<?php

use Core\View;

View::extend('app');
View::startSection('content');
?>

<h2>Bem-vindo!</h2>
<p>Essa é a página inicial do sistema.</p>

<?php View::endSection(); ?>
