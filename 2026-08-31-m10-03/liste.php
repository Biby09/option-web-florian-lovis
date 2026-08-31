<?php
require __DIR__ . '/bootstrap.php';
$evenements = $manager->tous();
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Événements</title>
<?php require __DIR__ . '/inc/header.php'; ?>
<ul>
<?php foreach ($evenements as $evenement): ?>
    <li>
        <a href="fiche.php?id=<?= (int) $evenement->getId() ?>">
            <?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>
