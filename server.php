<?php

/**
 * Provide a simple handscube develop php server.
 * "workbench.editor.enablePreview": false,
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$decodedUri = urldecode($uri);
if ($decodedUri !== '/' && file_exists(__DIR__ . '/public' . $decodedUri)) {
    return false;
} else {
    require_once __DIR__ . '/public/index.php';
}
