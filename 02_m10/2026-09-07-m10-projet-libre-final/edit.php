<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

if (!isset($_GET['slug'])) {
    header('Location: /index.php');
    exit;
}

$slug = $_GET['slug'];
$recette = $manager->getRecetteBySlug($slug);

if (isset($_POST['title'], $_POST['content'], $_POST['duration'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $duration = $_POST['duration'];
    $image = $_POST['image'];

    // Verification et enregistrement de l'image
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


        // Supprimer l'ancien fichier image s'il existe
        if ($recette->getImgPath() && file_exists($_SERVER['DOCUMENT_ROOT'] . $recette->getImgPath())) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $recette->getImgPath());
        }

        $recette->setImgPath($img_path);
    }


    $recette->setTitre($title);
    $recette->setDescription($content);
    $recette->setTime($duration);

    $manager->updateRecette($recette);

    header('Location: /index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php
$pageTitle = "Modifier la recette";
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    <main>
        <form class="recipe-form" action="" method="post" enctype="multipart/form-data">
            <h2>Modifier la recette</h2>
            <label for="title">Titre :</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recette->getTitre()); ?>"
                required>
            <label for="content">Description :</label>
            <textarea id="content" name="content"
                required><?php echo htmlspecialchars($recette->getDescription()); ?></textarea>
            <label for="duration">Durée (minutes) :</label>
            <input type="number" id="duration" name="duration" min="0"
                value="<?php echo htmlspecialchars($recette->getTime()); ?>" required>
            <label for="image">Image :</label>
            <input type="file" id="image" name="img_path" accept="image/*">
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </main>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>

</html>