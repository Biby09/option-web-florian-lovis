<?php
require __DIR__ . '/bootstrap.php';
$evenements = $manager->tous();
$titre = 'Événements';
require __DIR__ . '/inc/header.php';
?>
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
<?php require __DIR__ . '/inc/footer.php'; ?>
