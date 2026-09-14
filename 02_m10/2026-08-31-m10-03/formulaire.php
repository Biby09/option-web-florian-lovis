<?php
require __DIR__ . '/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$evenement = $id ? $manager->un($id) : null;
$nom = trim($_POST['nom'] ?? '');
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nom === '') {
    $erreur = 'Le nom est obligatoire.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nom !== '') {
    if ($evenement) {
        $evenement->setNom($nom);
        $manager->modifier($evenement);
    } else {
        $id = $manager->ajouter($nom);
        header('Location: fiche.php?id=' . $id);
        exit;
    }
    header('Location: liste.php');
    exit;
}

?>
<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Formulaire</title>
<p><a href="liste.php">Liste</a></p>
<h1><?= $evenement ? 'Modifier' : 'Ajouter' ?></h1>
<?php if ($erreur !== ''): ?>
    <p><?php echo htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="post">
    <label>
        Nom
        <input name="nom" required value="<?= htmlspecialchars($evenement?->getNom() ?? $nom, ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <button><?= $evenement ? 'Enregistrer' : 'Ajouter' ?></button>
</form>