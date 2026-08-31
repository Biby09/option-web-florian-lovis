<?php
require __DIR__ . '/bootstrap.php';
$evenements = $manager->tous();
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Événements</title>
<p><a href="liste.php">Liste</a> · <a href="formulaire.php">Ajouter</a></p>
<h1>Événements</h1>
<ul>
<?php foreach ($evenements as $evenement): ?>
    <li>
        <a href="fiche.php?id=<?= (int) $evenement->getId() ?>">
            <?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>
