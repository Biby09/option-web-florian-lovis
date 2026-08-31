<?php
class Evenement
{
    private int $id;
    private string $nom;
    private string $ville;
    private string $periode;

    public function hydrate(array $ligne): void
    {
        /* à vous : recopier le nom de la ligne dans l'objet */
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

    public function aLieuDans(string $ville): bool
    {
        /* à vous : comparer la ville de l'objet à la ville reçue */
    }
}
