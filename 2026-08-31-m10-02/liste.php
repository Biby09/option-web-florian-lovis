<?php
require __DIR__ . '/bootstrap.php';
$evenements = $manager->tous();
?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Événements</title>
<h1>Événements</h1>
<ul>
<?php foreach ($evenements as $evenement): ?>
    <li>
        <!-- à vous : un lien vers fiche.php?id=… avec le nom visible, via getId() et getNom() -->
    </li>
<?php endforeach; ?>
</ul>
