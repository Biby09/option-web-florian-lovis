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
        $this->evenementId = (int) $ligne['evenement_id'];
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
