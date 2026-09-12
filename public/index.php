<?php

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Router;

$router = new Router();
$router->dispatch();
