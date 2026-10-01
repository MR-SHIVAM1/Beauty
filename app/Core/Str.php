<?php
namespace App\Core;

class Str
{
    public static function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-') ?: 'item-' . substr(md5(uniqid('', true)), 0, 6);
    }
}
