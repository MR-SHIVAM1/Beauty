<?php
/**
 * Router for PHP's built-in dev server: let real static files (css/js/images)
 * be served directly, and send everything else through the front controller.
 */
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
