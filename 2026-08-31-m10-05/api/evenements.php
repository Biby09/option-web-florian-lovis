<?php
require __DIR__ . '/../bootstrap.php';

$ville = trim($_GET['ville'] ?? '');
$evenements = $ville === '' ? $manager->tous() : $manager->dansLaVille($ville);

$sortie = [];

foreach ($evenements as $evenement) {
    $sortie[] = [
        'id' => $evenement->getId(),
        'nom' => $evenement->getNom(),
        /* à vous : ajouter la ville */
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($sortie, JSON_UNESCAPED_UNICODE);
