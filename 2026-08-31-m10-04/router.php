<?php
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($chemin !== '/' && is_file(__DIR__ . $chemin)) {
    return false;
}

if (preg_match('#^/festivals/([a-z0-9-]+)/?$#', $chemin, $trouve)) {
    /* à vous : transmettre $trouve[1] à fiche.php */
    $_GET['slug'] = $trouve[1];
    require __DIR__ . '/fiche.php';
    return true;
}

if (preg_match('#^/concerts/([0-9]+)/?$#', $chemin, $trouve)) {
    /* à vous : transmettre l'identifiant à concert.php */
    $_GET['id'] = $trouve[1];
    require __DIR__ . '/concert.php';
    return true;
}

require __DIR__ . '/liste.php';
return true;
