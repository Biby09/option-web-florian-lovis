<?php
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

if ($base !== '' && str_starts_with($chemin, $base)) {
    $chemin = substr($chemin, strlen($base)) ?: '/';
}

if ($chemin !== '/' && is_file(__DIR__ . $chemin)) {
    return false;
}

if (preg_match('#(?:^|/)recette/create/?$#', $chemin)) {
    require __DIR__ . '/add.php';
    return true;
} elseif (preg_match('#(?:^|/)recette/([a-z0-9-]+)/?$#', $chemin, $trouve)) {
    $_GET['slug'] = $trouve[1];
    require __DIR__ . '/view.php';
    return true;
} elseif (preg_match('#(?:^|/)recette/delete/([a-z0-9-]+)/?$#', $chemin, $trouve)) {
    $_GET['slug'] = $trouve[1];
    require __DIR__ . '/delete.php';
    return true;
} elseif (preg_match('#(?:^|/)recette/edit/([a-z0-9-]+)/?$#', $chemin, $trouve)) {
    $_GET['slug'] = $trouve[1];
    require __DIR__ . '/edit.php';
    return true;
} else if ($chemin === '/' || $chemin === '/index.php') {
    require __DIR__ . '/index.php';
    return true;
}

require __DIR__ . '/index.php';
return true;