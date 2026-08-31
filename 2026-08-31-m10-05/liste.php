<?php
require __DIR__ . '/bootstrap.php';

$ville = trim($_GET['ville'] ?? '');
/* à vous : tous() si ville vide, dansLaVille() sinon */
$evenements = $manager->tous();
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Événements</title>
<h1>Événements</h1>
<form id="filtre" method="get">
    <label>
        Ville
        <input id="ville" name="ville" value="<?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <button>Filtrer</button>
</form>
<ul id="resultats">
<?php if ($evenements === []): ?>
    <li>Aucun résultat</li>
<?php else: ?>
    <?php foreach ($evenements as $evenement): ?>
        <li><?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?>
          — <?= htmlspecialchars($evenement->getVille(), ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>
<script src="js/filtre.js" defer></script>
