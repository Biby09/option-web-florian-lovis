<?php

class RecetteManager{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRecetteById(int $id): ?Recette
    {
        $stmt = $this->pdo->prepare('SELECT rec_id AS id, rec_slug AS slug, rec_titre AS titre, rec_description AS description, rec_img_path AS img_path, rec_duree AS time FROM recettes WHERE rec_id = :id');
        $stmt->execute(['id' => $id]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ligne) {
            $recette = new Recette();
            $recette->hydrate($ligne);
            return $recette;
        }
        return null;
    }

    public function getAllRecettes(): array
    {
        $stmt = $this->pdo->query('SELECT rec_id AS id, rec_slug AS slug, rec_titre AS titre, rec_description AS description, rec_img_path AS img_path, rec_duree AS time FROM recettes');
        $recettes = [];
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recette = new Recette();
            $recette->hydrate($ligne);
            $recettes[] = $recette;
        }
        return $recettes;
    }

    public function addRecette(Recette $recette): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO recettes (rec_slug, rec_titre, rec_description, rec_img_path, rec_duree) VALUES (:slug, :titre, :description, :img_path, :duree)');
        $stmt->execute([
            'slug' => $recette->getSlug(),
            'titre' => $recette->getTitre(),
            'description' => $recette->getDescription(),
            'img_path' => $recette->getImgPath(),
            'duree' => $recette->getTime()
        ]);
    }

    public function updateRecette(Recette $recette): void
    {
        $stmt = $this->pdo->prepare('UPDATE recettes SET rec_slug = :slug, rec_titre = :titre, rec_description = :description, rec_img_path = :img_path, rec_duree = :duree WHERE rec_id = :id');
        $stmt->execute([
            'slug' => $recette->getSlug(),
            'titre' => $recette->getTitre(),
            'description' => $recette->getDescription(),
            'img_path' => $recette->getImgPath(),
            'duree' => $recette->getTime(),
            'id' => $recette->getId()
        ]);
    }

    public function deleteRecette(Recette $recette): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM recettes WHERE rec_id = :id');
        $stmt->execute(['id' => $recette->getId()]);
    }
}