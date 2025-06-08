<?php
namespace App\Models;

use JsonSerializable;

class Vente implements JsonSerializable
{
    private $id;
    private $clientId;
    private $jeuId;
    private $dateVente;
    private $quantite;
    private $montantTotal;
    private $statut;
    
    // Public properties for joined data
    public $clientNom;
    public $jeuTitre;

    public function __construct($id, $clientId, $jeuId, $dateVente, $quantite, $montantTotal, $statut)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->jeuId = $jeuId;
        $this->dateVente = $dateVente;
        $this->quantite = $quantite;
        $this->montantTotal = $montantTotal;
        $this->statut = $statut;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'clientId' => $this->clientId,
            'jeuId' => $this->jeuId,
            'dateVente' => $this->dateVente,
            'quantite' => $this->quantite,
            'montantTotal' => $this->montantTotal,
            'statut' => $this->statut,
            'clientNom' => $this->clientNom,
            'jeuTitre' => $this->jeuTitre,
        ];
    }

    // Getters et Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getClientId() { return $this->clientId; }
    public function setClientId($clientId) { $this->clientId = $clientId; }

    public function getJeuId() { return $this->jeuId; }
    public function setJeuId($jeuId) { $this->jeuId = $jeuId; }

    public function getDateVente() { return $this->dateVente; }
    public function setDateVente($dateVente) { $this->dateVente = $dateVente; }

    public function getQuantite() { return $this->quantite; }
    public function setQuantite($quantite) { $this->quantite = $quantite; }

    public function getMontantTotal() { return $this->montantTotal; }
    public function setMontantTotal($montantTotal) { $this->montantTotal = $montantTotal; }

    public function getStatut() { return $this->statut; }
    public function setStatut($statut) { $this->statut = $statut; }
} 