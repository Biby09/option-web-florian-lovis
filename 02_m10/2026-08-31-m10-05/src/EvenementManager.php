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

    public function tous(): array
    {
        $lignes = $this->pdo
            ->query('SELECT id, nom, ville, periode FROM evenements ORDER BY nom')
            ->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];
        foreach ($lignes as $ligne) {
            $objets[] = $this->depuisLigne($ligne);
        }
        return $objets;
    }

    public function dansLaVille(string $ville): array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, ville, periode FROM evenements WHERE ville = ? ORDER BY nom'
        );
        $requete->execute([$ville]);
        $lignes = $requete->fetchAll(PDO::FETCH_ASSOC);
        $objets = [];
        foreach ($lignes as $ligne) {
            $objets[] = $this->depuisLigne($ligne);
        }
        return $objets;
    }
}
