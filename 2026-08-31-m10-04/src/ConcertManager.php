<?php
class ConcertManager
{
    public function __construct(private PDO $pdo) {}

    public function un(int $id): ?Concert
    {
        $requete = $this->pdo->prepare(
            'SELECT id, titre, date_concert, evenement_id FROM concerts WHERE id = ?'
        );
        $requete->execute([$id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);
        if ($ligne === false) {
            return null;
        }
        $concert = new Concert();
        $concert->hydrate($ligne);
        return $concert;
    }

    public function deLEvenement(int $evenementId): array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, titre, date_concert, evenement_id
             FROM concerts
             WHERE evenement_id = ?
             ORDER BY date_concert'
        );
        /* à vous : exécuter avec l'identifiant du parent */
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];
        foreach ($lignes as $ligne) {
            // transformez ensuite chaque ligne en Concert
        }
        return $objets;
    }
}
