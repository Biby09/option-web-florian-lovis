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
<title><?php /* à vous : le nom de l'objet, échappé */echo htmlspecialchars($evenement->getNom())?></title>
<h1><?php /* à vous : $evenement->getNom(), échappé */echo htmlspecialchars($evenement->getNom())?></h1>
<p><?php /* à vous : ville et période */ echo htmlspecialchars($evenement->getVille()) . ' - ' . htmlspecialchars($evenement->getPeriode()); ?></p>
<p><?php /* à vous : afficher la phrase Nyon seulement si aLieuDans('Nyon') */ if ($evenement->aLieuDans('Nyon')) { echo 'Nyon'; } ?></p>
<p><a href="liste.php">Retour à la liste</a></p>
