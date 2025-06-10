<?php
namespace App\Models;

use JsonSerializable;

abstract class Jeu implements JsonSerializable
{
    protected $id;
    protected $titre;
    protected $prix;
    protected $genre;
    protected $editeur;
    protected $stockDisponible;
    protected $typeJeu;

    public function __construct($id, $titre, $prix, $genre, $editeur, $stockDisponible, $typeJeu)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->prix = $prix;
        $this->genre = $genre;
        $this->editeur = $editeur;
        $this->stockDisponible = $stockDisponible;
        $this->typeJeu = $typeJeu;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'prix' => $this->prix,
            'genre' => $this->genre,
            'editeur' => $this->editeur,
            'stockDisponible' => $this->stockDisponible,
            'type' => $this->typeJeu,
        ];
    }

    // Getters et Setters
    public function getId() {
        return $this->id; 
    }
    public function setId($id) { 
        $this->id = $id; 
    }

    public function getTitre() { 
        return $this->titre; 
    }
    public function setTitre($titre) { 
        $this->titre = $titre; 
    }

    public function getPrix() { 
        return $this->prix; 
    }
    public function setPrix($prix) { 
        $this->prix = $prix; 
    }

    public function getGenre() { 
        return $this->genre; 
    }
    public function setGenre($genre) { 
        $this->genre = $genre; 
    }

    public function getEditeur() { 
        return $this->editeur; 
    }
    public function setEditeur($editeur) { 
        $this->editeur = $editeur; 
    }

    public function getStockDisponible() { 
        return $this->stockDisponible; 
    }
    public function setStockDisponible($stockDisponible) { 
        $this->stockDisponible = $stockDisponible; 
    }

    public function getTypeJeu() { 
        return $this->typeJeu; 
    }
    public function setTypeJeu($typeJeu) { 
        $this->typeJeu = $typeJeu; 
    }
} 