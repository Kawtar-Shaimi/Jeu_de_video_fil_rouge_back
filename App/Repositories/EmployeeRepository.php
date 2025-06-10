<?php

namespace App\Repositories;
use App\Models\Employee;
use Core\Facades\RepositoryMutations;
use PDO;

class EmployeeRepository extends RepositoryMutations
{

    public function __construct()
    {
        parent::__construct('employees');
    }

    function findAllEmployees(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName;")->fetchAll((PDO::FETCH_ASSOC));
        return $this->arrayMapper($data);
    }

    public function findEmployeesWithSearch(?string $search = null): array
    {
        if (empty($search)) {
            return $this->findAllEmployees();
        }
        
        $sql = "SELECT * FROM $this->tableName WHERE name LIKE :search OR email LIKE :search";
        $stmt = $this->db->getPdo()->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->execute(['search' => $searchParam]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->arrayMapper($data);
    }

    public function findById($id): Employee
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Employee with ID $id not found.");
        }
        return $this->mapper($data);
    }


    public function mapper(array $data): Employee
    {
        $id = $this->get($data, 'id');
        $salary = $this->get($data, 'salary');
        $name = $this->get($data, 'name');
        $email = $this->get($data, 'email');
        $photo = $this->get($data, 'photo');
        $password = $this->get($data, 'password');
        $employee = new Employee($id, $name, $salary, $email, $password, $photo);
        return $employee;
    }

}