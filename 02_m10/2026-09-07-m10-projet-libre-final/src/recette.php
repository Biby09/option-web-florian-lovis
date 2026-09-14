<?php

class Recette
{
    private int $id;
    private string $slug;
    private string $titre;
    private string $description;
    private string $img_path;
    private string $time;

    public function hydrate(array $ligne): void
    {
        $this->id = (int) $ligne['id'];
        $this->slug = $ligne['slug'];
        $this->titre = $ligne['titre'];
        $this->description = $ligne['description'];
        $this->img_path = $ligne['img_path'];
        $this->time = $ligne['time'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getSlug(): string
    {
        if (empty($this->slug)) {
            $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->titre))) .'-'. uniqid();
        }
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImgPath(): string
    {
        return $this->img_path;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setImgPath(string $img_path): void
    {
        $this->img_path = $img_path;
    }

    public function setTime(string $time): void
    {
        $this->time = $time;
    }

}