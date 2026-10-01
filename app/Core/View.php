<?php
namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layout'): void
    {
        // Path/layout are resolved into double-underscore names *before* extract()
        // runs, because extract($data) can otherwise clobber $view/$layout if a
        // caller's data array (e.g. one built from get_defined_vars()) happens to
        // contain keys named "view" or "layout".
        $__viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        if (!file_exists($__viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        $__layoutPath = $layout !== null ? dirname(__DIR__) . '/Views/' . $layout . '.php' : null;

        extract($data);

        ob_start();
        require $__viewPath;
        $content = ob_get_clean();

        if ($__layoutPath === null) {
            echo $content;
            return;
        }

        require $__layoutPath;
    }

    public static function partial(string $view, array $data = []): void
    {
        $__partialPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        extract($data);
        require $__partialPath;
    }
}
