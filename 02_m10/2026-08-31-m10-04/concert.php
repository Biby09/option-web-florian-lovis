<?php
require __DIR__ . '/bootstrap.php';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$concert = $id ? $concerts->un($id) : null;

if ($concert === null) {
    http_response_code(404);
    exit('Cet élément n’existe pas.');
}

$titre = $concert->getTitre();
require __DIR__ . '/inc/header.php';
?>
<h1><?= htmlspecialchars($concert->getTitre(), ENT_QUOTES, 'UTF-8') ?></h1>
<p>Date : <?= htmlspecialchars($concert->getDateConcert(), ENT_QUOTES, 'UTF-8') ?></p>
<p><a href="/">Retour à la liste</a></p>
<?php require __DIR__ . '/inc/footer.php'; ?>
