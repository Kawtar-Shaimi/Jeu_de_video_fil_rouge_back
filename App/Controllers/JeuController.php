<?php

namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\JeuDefault;
use App\Services\Interfaces\JeuService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1')]
class JeuController extends Controller implements ResourceController
{

    private JeuService $jeuService;
    public function __construct()
    {
        parent::__construct();
        $this->jeuService = new JeuDefault();
    }

    #[Description("Récupère la liste des jeux avec possibilité de filtrer via les paramètres de requête.")]
    public function index()
    {
        try {
            $params = $this->request->param();
            $this->json($this->jeuService->getJeux($params));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Affiche les détails d'un jeu en utilisant son identifiant.")]
    public function show($id)
    {
        try {
            return $this->json($this->jeuService->getJeu($id));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Met à jour les informations d'un jeu.")]
    public function update($id)
    {
        try {
            return $this->json($this->jeuService->updateJeu($id, $this->request->all()));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Crée un nouveau jeu (PC ou Console) avec les champs requis : titre, prix, genre, editeur, stockDisponible, type.")]
    public function store()
    {
        try {
            $data = $this->request->all();
            return $this->json($this->jeuService->createJeu($data), 201);
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 400);
        }
    }

    #[Description("Supprime un jeu à partir de son identifiant.")]
    public function destroy($id)
    {
            return $this->json($this->jeuService->deleteJeu($id));
    }

} 