<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

if (!isset($_GET['slug']) && !isset($_GET['id'])) {
    header('Location: /index.php');
    exit;
}

$recette = isset($_GET['slug'])
    ? $manager->getRecetteBySlug($_GET['slug'])
    : $manager->getRecetteById((int) $_GET['id']);
if (!$recette) {
    header('Location: /index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php $title = htmlspecialchars($recette->getTitre()); ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    <main class="recipe-detail">
        <a href="/index.php">← Retour au répertoire</a>
        <h2><?php echo htmlspecialchars($recette->getTitre()); ?></h2>
        <p><?php echo htmlspecialchars($recette->getDescription()); ?></p>
        <p>Durée : <?php echo htmlspecialchars($recette->getTime()); ?> minutes</p>
        <?php if ($recette->getImgPath()) : ?>
            <img src="<?php echo htmlspecialchars($recette->getImgPath()); ?>" alt="<?php echo htmlspecialchars($recette->getTitre()); ?>">
        <?php endif; ?>
        <a href="edit/<?php echo htmlspecialchars((string) $recette->getSlug()); ?>">Modifier la recette</a>
        <a href="delete/<?php echo htmlspecialchars((string) $recette->getSlug()); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">Supprimer la recette</a>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
