<?php
class EvenementManager
{
    public function __construct(private PDO $pdo) {}

    public function un(int $id): ?Evenement
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, ville, periode FROM evenements WHERE id = ?'
        );
        $requete->execute([$id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }

        /* à vous : fabriquer, hydrater et retourner un Evenement */

        $evenement = new Evenement();
        $evenement->hydrate($ligne);
        return $evenement;
    }

    public function tous(): array
    {
        $lignes = $this->pdo
            ->query('SELECT id, nom, ville, periode FROM evenements ORDER BY nom')
            ->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];

        foreach ($lignes as $ligne) {
            $evenement = new Evenement();
            $evenement->hydrate($ligne);
            $objets[] = $evenement;
        }

        return $objets;
    }
}
