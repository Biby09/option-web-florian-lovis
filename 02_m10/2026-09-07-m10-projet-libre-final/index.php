<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
?>

<!DOCTYPE html>
<html lang="fr">
<?php $title = 'Répertoire de recette'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
    <main>
        <div class="search-wrap">
            <input class="search-input" type="text" placeholder="Rechercher..." aria-label="Rechercher une recette">
            <div id="suggestions" class="suggestions" hidden></div>
        </div>
        <div class="page-heading">
            <h2>Les recettes du répertoire</h2>
            <p>Des idées simples à cuisiner, réunies au même endroit.</p>
        </div>
        <?php
        if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
            $query = trim($_GET['query']);
            $recettes = $manager->searchRecettes($query);
        } else {
            $recettes = $manager->getAllRecettes();
        }
        if ($recettes) {
            echo '<ul class="recipe-grid">';
            foreach ($recettes as $recette) {
                echo '<li class="recipe-card">';
                echo '<a href="/recette/' . rawurlencode($recette->getSlug()) . '">';
                if ($recette->getImgPath()) {
                    echo '<img class="recipe-card-image" src="' . htmlspecialchars($recette->getImgPath()) . '" alt="' . htmlspecialchars($recette->getTitre()) . '">';
                } else {
                    echo '<div class="recipe-card-placeholder" aria-hidden="true">🍴</div>';
                }
                echo '<div class="recipe-card-content">';
                echo '<h3>' . htmlspecialchars($recette->getTitre()) . '</h3>';
                echo '<p>' . htmlspecialchars($recette->getDescription()) . '</p>';
                echo '<span class="recipe-time">Durée : ' . htmlspecialchars($recette->getTime()) . ' minutes</span>';
                echo '</div></a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p class="empty-state">Aucune recette trouvée.</p>';
        }
        ?>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
    <script src="/js/barre-recherche.js"></script>
</body>
</html>