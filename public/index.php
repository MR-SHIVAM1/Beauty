<?php
declare(strict_types=1);

session_start();

require dirname(__DIR__) . '/app/Core/Autoload.php';
require dirname(__DIR__) . '/app/Core/helpers.php';

use App\Core\Request;
use App\Core\Router;

$router = new Router();
require dirname(__DIR__) . '/app/routes.php';

$router->dispatch(Request::method(), Request::path());
