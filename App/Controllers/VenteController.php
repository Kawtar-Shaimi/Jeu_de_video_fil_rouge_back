<?php

namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\VenteDefault;
use App\Services\Interfaces\VenteService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1')]
class VenteController extends Controller implements ResourceController
{

    private VenteService $venteService;
    public function __construct()
    {
        parent::__construct();
        $this->venteService = new VenteDefault();
    }

    #[Description("Récupère la liste des ventes.")]
    public function index()
    {
        try {
            $params = $this->request->param();
            $this->json($this->venteService->getVentes($params));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Affiche les détails d'une vente en utilisant son identifiant.")]
    public function show($id)
    {
        try {
            return $this->json($this->venteService->getVente($id));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Met à jour le statut d'une vente.")]
    public function update($id)
    {
        try {
            $data = $this->request->all();
            return $this->json($this->venteService->updateStatutVente($id, $data['statut']));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Crée une nouvelle vente avec les champs : clientId, jeuId, quantite.")]
    public function store()
    {
        try{
            $data = $this->request->all();
            return $this->json($this->venteService->createVente($data['clientId'], $data['jeuId'], $data['quantite']), 201);
        }
           catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Supprime une vente à partir de son identifiant.")]
    public function destroy($id)
    {
            return $this->json($this->venteService->deleteVente($id));
    }

} 