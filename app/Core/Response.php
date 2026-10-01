<?php
namespace App\Core;

class Response
{
    public static function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404', []);
        exit;
    }
}
