<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/recette.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/recetteManager.php';

$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$manager = new RecetteManager($pdo);