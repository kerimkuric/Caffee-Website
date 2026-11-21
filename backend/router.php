<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$file = __DIR__ . $uri;

if ($uri !== '/' && file_exists($file)) {
    if (is_dir($file)) {
        foreach (['index.php', 'index.html'] as $index) {
            $candidate = rtrim($file, '/\\') . DIRECTORY_SEPARATOR . $index;
            if (file_exists($candidate)) {
                require $candidate;
                return true;
            }
        }
    } else {
        return false;
    }
}

require __DIR__ . '/index.php';
