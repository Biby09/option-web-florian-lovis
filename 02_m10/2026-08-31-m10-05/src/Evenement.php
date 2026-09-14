<?php
class Evenement
{
    private int $id;
    private string $nom;
    private string $ville;
    private string $periode;


    public function hydrate(array $ligne): void
    {
        $this->id = (int) $ligne['id'];
        $this->nom = $ligne['nom'];
        $this->ville = $ligne['ville'];
        $this->periode = $ligne['periode'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getPeriode(): string
    {
        return $this->periode;
    }
}
