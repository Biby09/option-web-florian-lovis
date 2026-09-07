<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $time = $_POST['time'] ?? '';

    //Enregistrement de l'image
    $img_path = $_FILES['img_path']['name'] ?? '';
    if ($img_path) {
        // Vérification du type MIME du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['img_path']['tmp_name']);
        if (strpos($mime_type, 'image/') !== 0) {
            $img_path = '';
            $message = 'Le fichier téléchargé n\'est pas une image valide.';
        }
        // Création du répertoire de destination s'il n'existe pas
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = uniqid() . '_' . basename($img_path);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($_FILES['img_path']['tmp_name'], $target_file);
        $img_path = '/uploads/' . $file_name;
    }

    if ($titre && $description) {
        $recette = new Recette();
        $recette->setTitre($titre);
        $recette->setDescription($description);
        $recette->setTime($time);
        $recette->setImgPath($img_path);
        $manager->addRecette($recette);
        header('Location: /index.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<?php $title = 'Ajouter une recette'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    <main>
        <form class="recipe-form" method="post" enctype="multipart/form-data">
        <h2>Ajouter une recette</h2>
            <?php if (!empty($message)) : ?>
                <p class="form-error"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
            <label for="time">Durée (minutes) :</label>
            <input type="text" id="time" name="time" required>
            <label for="img_path">Image :</label>
            <input type="file" id="img_path" name="img_path" accept="image/*">
            <button type="submit">Ajouter</button>
        </form>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>