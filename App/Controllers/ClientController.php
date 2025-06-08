<?php

namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\ClientDefault;
use App\Services\Interfaces\ClientService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1')]
class ClientController extends Controller implements ResourceController
{

    private ClientService $clientService;
    public function __construct()
    {
        parent::__construct();
        $this->clientService = new ClientDefault();
    }

    #[Description("Récupère la liste des clients avec possibilité de filtrer via les paramètres de requête.")]
    public function index()
    {
        try {
            $params = $this->request->param();
            $this->json($this->clientService->getClients($params));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Affiche les détails d'un client en utilisant son identifiant.")]
    public function show($id)
    {
        try {
            return $this->json($this->clientService->getClient($id));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Met à jour les informations d'un client.")]
    public function update($id)
    {
        try {
            return $this->json($this->clientService->updateClient($id, $this->request->all()));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Crée un nouveau client avec les champs : nom, email, phone.")]
    public function store()
    {
        try{
            $data = $this->request->all();
            return $this->json($this->clientService->createClient($data['nom'], $data['email'], $data['phone']), 201);
        }
           catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Supprime un client à partir de son identifiant.")]
    public function destroy($id)
    {
            return $this->json($this->clientService->deleteClient($id));
     
    }

} 