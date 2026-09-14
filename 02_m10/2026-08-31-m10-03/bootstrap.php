<?php
require __DIR__ . '/config.php';
require __DIR__ . '/src/Evenement.php';
require __DIR__ . '/src/EvenementManager.php';

$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$manager = new EvenementManager($pdo);
