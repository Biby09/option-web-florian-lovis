<?php
require __DIR__ . '/bootstrap.php';

$slug = $_GET['slug'] ?? '';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($slug !== '') {
    $evenement = $manager->parSlug($slug);
} else {
    $evenement = $id ? $manager->un($id) : null;
}

if ($evenement === null) {
    http_response_code(404);
    exit('Cet élément n’existe pas.');
}

$titre = $evenement->getNom();
require __DIR__ . '/inc/header.php';
?>
<h1><?= htmlspecialchars($evenement->getNom(), ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($evenement->getVille(), ENT_QUOTES, 'UTF-8') ?>
  — <?= htmlspecialchars($evenement->getPeriode(), ENT_QUOTES, 'UTF-8') ?></p>
<h2>Concerts</h2>
<ul>
<?php foreach ($concerts->deLEvenement($evenement->getId()) as $concert): ?>
    <li><a href="/concerts/<?= $concert->getId() ?>"><?= htmlspecialchars($concert->getTitre(), ENT_QUOTES, 'UTF-8') ?></a></li>
<?php endforeach; ?>
</ul>
<?php require __DIR__ . '/inc/footer.php'; ?>
