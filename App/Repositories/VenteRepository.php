<?php

namespace App\Repositories;
use App\Models\Vente;
use Core\Facades\RepositoryMutations;
use PDO;

class VenteRepository extends RepositoryMutations
{

    public function __construct()
    {
        parent::__construct('ventes');
    }

    function findAllVentes(): array
    {
        $sql = "SELECT v.*, c.nom as clientNom, j.titre as jeuTitre 
                FROM $this->tableName v 
                LEFT JOIN clients c ON v.clientId = c.id 
                LEFT JOIN jeux j ON v.jeuId = j.id";
        $data = $this->db->getPdo()->query($sql)->fetchAll((PDO::FETCH_ASSOC));
        return $this->arrayMapper($data);
    }

    public function findById($id): Vente
    {
        $sql = "SELECT v.*, c.nom as clientNom, j.titre as jeuTitre 
                FROM $this->tableName v 
                LEFT JOIN clients c ON v.clientId = c.id 
                LEFT JOIN jeux j ON v.jeuId = j.id 
                WHERE v.id = :id LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Vente with ID $id not found.");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Vente
    {
        $id = $this->get($data, 'id');
        $clientId = $this->get($data, 'clientId');
        $jeuId = $this->get($data, 'jeuId');
        $dateVente = $this->get($data, 'dateVente');
        $quantite = $this->get($data, 'quantite');
        $montantTotal = $this->get($data, 'montantTotal');
        $statut = $this->get($data, 'statut');
        
        $vente = new Vente($id, $clientId, $jeuId, $dateVente, $quantite, $montantTotal, $statut);
        
        // Add client and jeu names if available
        if (isset($data['clientNom'])) {
            $vente->clientNom = $data['clientNom'];
        }
        if (isset($data['jeuTitre'])) {
            $vente->jeuTitre = $data['jeuTitre'];
        }
        
        return $vente;
    }

} 