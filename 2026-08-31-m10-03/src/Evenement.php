<?php
class Evenement
{
    private int $id;
    private string $nom;
    private string $ville;
    private string $periode;
    private string $description = '';

    public function hydrate(array $ligne): void
    {
        $this->id = (int) $ligne['id'];
        $this->nom = $ligne['nom'];
        $this->ville = $ligne['ville'];
        $this->periode = $ligne['periode'];
        $this->description = $ligne['description'] ?? '';
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function aLieuDans(string $ville): bool
    {
        return $this->ville === $ville;
    }
}
