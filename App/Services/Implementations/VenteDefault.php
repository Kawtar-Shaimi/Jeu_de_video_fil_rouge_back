<?php
namespace App\Services\Implementations;
use App\Repositories\VenteRepository;
use App\Repositories\JeuRepository;
use App\Services\Interfaces\VenteService;
use App\Models\Vente;
use ErrorException;

class VenteDefault implements VenteService
{

    public function __construct(
        private VenteRepository $venteRepository = new VenteRepository(),
        private JeuRepository $jeuRepository = new JeuRepository()
    )
    {
    }

    public function getVente($id): Vente
    {
        return $this->venteRepository->findById($id);
    }

    public function getVentes(?array $data): array
    {
        return $this->venteRepository->findAllVentes();
    }

    public function createVente(int $clientId, int $jeuId, int $quantite): Vente
    {
        // Récupérer le jeu pour le prix
        $jeu = $this->jeuRepository->findById($jeuId);
        
        // Vérifier le stock
        if ($jeu->getStockDisponible() < $quantite) {
            throw new ErrorException("Stock insufficient");
        }

        // Calculer montant total = (quantite × prix) + TVA (20%)
        $montantHT = $quantite * $jeu->getPrix();
        $montantTotal = $montantHT * 1.20; // TVA 20%

        $vente = new Vente(null, $clientId, $jeuId, date('Y-m-d H:i:s'), $quantite, $montantTotal, 'EN_ATTENTE');
        
        if($this->venteRepository->save([
            'clientId' => $clientId, 
            'jeuId' => $jeuId,
            'dateVente' => date('Y-m-d H:i:s'),
            'quantite' => $quantite,
            'montantTotal' => $montantTotal,
            'statut' => 'EN_ATTENTE'
            ])) {
            
            // Mettre à jour le stock
            $nouveauStock = $jeu->getStockDisponible() - $quantite;
            $this->jeuRepository->update(['stockDisponible' => $nouveauStock], ['id' => $jeuId]);
            
            return $vente;
        }
        throw new ErrorException("We cant do this now");
    }

    public function updateStatutVente(int $venteId, string $statut): Vente
    {
        $vente = $this->venteRepository->findById($venteId);
        if ($this->venteRepository->update(['statut' => $statut], ['id' => $venteId])) {
            return $this->venteRepository->findById($venteId);
        }
        throw new ErrorException("We cant do this now");
    }

    public function deleteVente(int $venteId): Vente
    {
        $vente = $this->venteRepository->findById($venteId);
        if ($this->venteRepository->delete(['id' => $venteId])) {
            return $vente;
        }
        throw new ErrorException("We cant do this now");
    }

} 