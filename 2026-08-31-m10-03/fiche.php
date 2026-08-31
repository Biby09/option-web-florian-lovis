<?php
require __DIR__ . '/bootstrap.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$evenement = $id ? $manager->un($id) : null;

if ($evenement === null) {
    http_response_code(404);
    exit('Cet élément n’existe pas.');
}
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title><?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?></title>
<p><a href="liste.php">Liste</a> · <a href="formulaire.php">Ajouter</a></p>
<h1><?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($evenement->getVille(), ENT_QUOTES, 'UTF-8') ?>
  — <?= htmlspecialchars($evenement->getPeriode(), ENT_QUOTES, 'UTF-8') ?></p>
<p><?= htmlspecialchars($evenement->getDescription(), ENT_QUOTES, 'UTF-8') ?></p>
<p>
  <a href="formulaire.php?id=<?= (int) $evenement->getId() ?>">Modifier</a>
  ·
  <a href="supprimer.php?id=<?= (int) $evenement->getId() ?>">Supprimer</a>
</p>
