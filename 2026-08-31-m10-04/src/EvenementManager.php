<?php
class EvenementManager
{
    public function __construct(private PDO $pdo) {}

    private function depuisLigne(array $ligne): Evenement
    {
        $evenement = new Evenement();
        $evenement->hydrate($ligne);
        return $evenement;
    }

    public function un(int $id): ?Evenement
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, ville, periode, description, slug FROM evenements WHERE id = ?'
        );
        $requete->execute([$id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);
        return $ligne === false ? null : $this->depuisLigne($ligne);
    }

    public function parSlug(string $slug): ?Evenement
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, ville, periode, description, slug FROM evenements WHERE slug = ?'
        );
        /* à vous : exécuter avec le slug */
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);
        return $ligne === false ? null : $this->depuisLigne($ligne);
    }

    public function tous(): array
    {
        $lignes = $this->pdo
            ->query('SELECT id, nom, ville, periode, description, slug FROM evenements ORDER BY nom')
            ->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];
        foreach ($lignes as $ligne) {
            $objets[] = $this->depuisLigne($ligne);
        }
        return $objets;
    }
}
