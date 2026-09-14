<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$query = $input['query'] ?? '';

$results = [];

if ($query !== '') {
    $results = $manager->searchRecettes($query);
}

$results = array_map(function ($recette) {
    return [
        'slug' => $recette->getSlug(),
        'titre' => $recette->getTitre(),
    ];
}, $results);

echo json_encode($results);
?>