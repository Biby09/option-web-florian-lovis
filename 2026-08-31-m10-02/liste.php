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
        <a href="fiche.php?id=<?php echo $evenement->getId(); ?>">
            <?php echo htmlspecialchars($evenement->getNom()); ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>
