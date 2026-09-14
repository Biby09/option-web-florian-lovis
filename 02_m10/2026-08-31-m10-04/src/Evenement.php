<?php
class Evenement
{
    private int $id;
    private string $nom;
    private string $ville;
    private string $periode;
    private string $description = '';
    private string $slug = '';

    public function hydrate(array $ligne): void
    {
        $this->id = (int) $ligne['id'];
        $this->nom = $ligne['nom'];
        $this->ville = $ligne['ville'];
        $this->periode = $ligne['periode'];
        $this->description = $ligne['description'] ?? '';
        $this->slug = $ligne['slug'] ?? '';
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

    public function getSlug(): string
    {
        return $this->slug;
    }
}
