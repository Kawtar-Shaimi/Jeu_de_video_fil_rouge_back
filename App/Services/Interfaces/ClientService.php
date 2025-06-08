<?php
namespace App\Services\Interfaces;
use App\Models\Client;

interface ClientService
{
    public function getClient(int $id): Client;

    public function getClients(?array $filters): array;

    public function updateClient(int $clientId, array $data): Client;

    public function createClient($nom, $email, $phone): Client;

    public function deleteClient(int $clientId): Client;
} 