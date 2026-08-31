<?php
class Concert
{
    private int $id;
    private string $titre;
    private string $dateConcert;
    private int $evenementId;

    public function hydrate(array $ligne): void
    {
        $this->id = (int) $ligne['id'];
        $this->titre = $ligne['titre'];
        $this->dateConcert = $ligne['date_concert'];
        /* à vous : ranger evenement_id dans l'objet */
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDateConcert(): string
    {
        return $this->dateConcert;
    }

    public function getEvenementId(): int
    {
        return $this->evenementId;
    }
}
