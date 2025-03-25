<?php

require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../boot/app.php';

use Core\Router;

use App\Middlewares\CsrfMiddleware;

(new CsrfMiddleware())->handle();

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch();