<?php
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($chemin !== '/' && is_file(__DIR__ . $chemin)) {
    return false;
}

if (preg_match('#^/festivals/([a-z0-9-]+)/?$#', $chemin, $trouve)) {
    /* à vous : transmettre $trouve[1] à fiche.php */
}

if (preg_match('#^/concerts/([0-9]+)/?$#', $chemin, $trouve)) {
    /* à vous : transmettre l'identifiant à concert.php */
}

require __DIR__ . '/liste.php';
return true;
