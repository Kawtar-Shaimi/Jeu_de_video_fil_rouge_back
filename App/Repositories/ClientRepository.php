<?php

namespace App\Repositories;
use App\Models\Client;
use Core\Facades\RepositoryMutations;
use PDO;

class ClientRepository extends RepositoryMutations
{

    public function __construct()
    {
        parent::__construct('clients');
    }

    function findAllClients(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName;")->fetchAll((PDO::FETCH_ASSOC));
        return $this->arrayMapper($data);
    }

    public function findClientsWithSearch(?string $search = null): array
    {
        if (empty($search)) {
            return $this->findAllClients();
        }
        
        $sql = "SELECT * FROM $this->tableName WHERE nom LIKE :search OR email LIKE :search";
        $stmt = $this->db->getPdo()->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->execute(['search' => $searchParam]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->arrayMapper($data);
    }

    public function findById($id): Client
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Client with ID $id not found.");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Client
    {
        $id = $this->get($data, 'id');
        $nom = $this->get($data, 'nom');
        $email = $this->get($data, 'email');
        $phone = $this->get($data, 'phone');
        $client = new Client($id, $nom, $email, $phone);
        return $client;
    }

} 