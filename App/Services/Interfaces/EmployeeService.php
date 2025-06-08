<?php
namespace App\Services\Interfaces;
use App\Models\Employee;

interface EmployeeService
{
    public function getEmployee(int $id): Employee;

    public function getEmployees(?array $filters): array;

    public function updateEmployee(int $employeeId, array $data) : Employee;

    public function augmentSalary(int $employeeId, float $augmentation): Employee;

    public function createEmployee($name, $salary, $email, $password, $photo) : Employee;

    public function deleteEmployee(int $employeeId): Employee;
}
