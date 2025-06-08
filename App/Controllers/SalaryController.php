<?php
namespace App\Controllers;

use Core\Controller;
use Core\Decorators\Route;
use Core\Router\RouteMethod;
use App\Services\Implementations\EmployeeDefault;
use App\Services\Interfaces\EmployeeService;
use Core\Decorators\Description;

#[Route('/api/v1/salaries')]
class SalaryController extends Controller
{
    private EmployeeService $employeeService;
    public function __construct()
    {
        parent::__construct();
        $this->employeeService = new EmployeeDefault();

    }
    

    #[Route('{employeeId}', method:RouteMethod::PATCH)]
    #[Description("Cette méthode permet au service RH d'augmenter le salaire d'un employé dans l'entreprise.")]
    public function augmentSalary($employeeId)
    {
        try {
            $augmentation = $this->request->input("augmentation");
            if($augmentation > 0){
                return $this->json($this->employeeService->augmentSalary($employeeId, $augmentation));
            }
            return $this->json(["error" => "We can't apply an augmentation with this value (use a positive value)"], 409);
        } catch (\Exception $e) {
            return $this->json(["error" => $e->getMessage()], 500);
        }
    }

}
