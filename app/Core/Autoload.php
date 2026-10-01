<?php
/**
 * Zero-dependency PSR-4-ish autoloader: App\Foo\Bar -> app/Foo/Bar.php
 */

spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = dirname(__DIR__) . '/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});
