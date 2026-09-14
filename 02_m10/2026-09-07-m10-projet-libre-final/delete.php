<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

if (!isset($_GET['slug'])) {

header('Location: /index.php');
    exit;
}

$slug = $_GET['slug'];

$recette = $manager->getRecetteBySlug($slug);

if ($recette) {
    $manager->deleteRecette($recette);
}

header('Location: /index.php');
exit;