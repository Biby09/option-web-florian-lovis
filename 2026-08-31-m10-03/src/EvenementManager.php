<?php
class EvenementManager
{
    public function __construct(private PDO $pdo) {}

    public function un(int $id): ?Evenement
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, ville, periode, description FROM evenements WHERE id = ?'
        );
        $requete->execute([$id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }

        $evenement = new Evenement();
        $evenement->hydrate($ligne);
        return $evenement;
    }

    public function tous(): array
    {
        $lignes = $this->pdo
            ->query('SELECT id, nom, ville, periode, description FROM evenements ORDER BY nom')
            ->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];

        foreach ($lignes as $ligne) {
            $evenement = new Evenement();
            $evenement->hydrate($ligne);
            $objets[] = $evenement;
        }

        return $objets;
    }

    public function ajouter(string $nom): int
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO evenements (nom) VALUES (?)'
        );
        /* à vous : exécuter avec le nom */
        return (int) $this->pdo->lastInsertId();
    }

    public function modifier(Evenement $evenement): void
    {
        $requete = $this->pdo->prepare(
            'UPDATE evenements SET nom = ?, ville = ?, periode = ?, description = ? WHERE id = ?'
        );
        /* à vous : exécuter avec les valeurs de l'objet, l'id en dernier */
    }

    public function supprimer(int $id): void
    {
        $requete = $this->pdo->prepare(
            'DELETE FROM evenements WHERE id = ?'
        );
        /* à vous : exécuter avec l'identifiant */
    }
}
