<?php
// router.php pour le serveur intégré PHP
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Si c'est un fichier qui existe, le servir normalement
if (file_exists(__DIR__ . $path)) {
    return false;
}

// Sinon, rediriger vers index.php
$_SERVER['REQUEST_URI'] = $uri;
require_once __DIR__ . '/index.php'; 