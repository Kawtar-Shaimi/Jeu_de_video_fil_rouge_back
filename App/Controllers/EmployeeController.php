<?php

namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\EmployeeDefault;
use App\Services\Interfaces\EmployeeService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1')]
class EmployeeController extends Controller implements ResourceController
{

    private EmployeeService $employeeService;
    public function __construct()
    {
        parent::__construct();
        $this->employeeService = new EmployeeDefault();
    }

    #[Description("Récupère la liste paginée des employés avec possibilité de filtrer via les paramètres de requête.")]
    public function index()
    {
        try {
            $params = $this->request->param();
            $this->json($this->employeeService->getEmployees($params));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Affiche les détails d’un employé en utilisant son identifiant.")]
    public function show($id)
    {
        try {
            return $this->json($this->employeeService->getEmployee($id));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Met à jour le salaire d’un employé avec des nouvelles valeurs.")]
    public function update($id)
    {
        try {
            return $this->json($this->employeeService->updateEmployee($id, $this->request->all()));
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Crée un nouvel employé avec les champs : name, salary, email, password et photo.")]
    public function store()
    {
        try{
            $data = $this->request->all();
            $photo = $this->request->file('photo');
            return $this->json($this->employeeService->createEmployee($data['name'], $data['salary'], $data['email'], $data['password'], $photo), 201);
        }
           catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 404);
        }
    }

    #[Description("Supprime un employé à partir de son identifiant.")]
    public function destroy($id)
    {
            return $this->json($this->employeeService->deleteEmployee($id));
     
    }

}