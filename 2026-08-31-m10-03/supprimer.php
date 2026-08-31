<?php
require __DIR__ . '/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$evenement = $id ? $manager->un($id) : null;

if ($evenement === null) {
    http_response_code(404);
    exit('Cet élément n’existe pas.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* à vous : supprimer via le manager, rediriger vers liste.php, exit */
}
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Supprimer</title>
<p><a href="liste.php">Liste</a></p>
<h1>Supprimer <?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?> ?</h1>
<form method="post">
    <input type="hidden" name="id" value="<?= $evenement->getId() ?>">
    <button>Confirmer la suppression</button>
</form>
