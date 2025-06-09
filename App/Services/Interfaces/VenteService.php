<?php
namespace App\Services\Interfaces;
use App\Models\Vente;

interface VenteService
{
    public function getVente(int $id): Vente;

    public function getVentes(?array $filters): array;

    public function createVente(int $clientId, int $jeuId, int $quantite): Vente;

    public function updateStatutVente(int $venteId, string $statut): Vente;

    public function deleteVente(int $venteId): Vente;
} 