<?php
namespace App\Services\Implementations;
use App\Repositories\ClientRepository;
use App\Services\Interfaces\ClientService;
use App\Models\Client;
use ErrorException;

class ClientDefault implements ClientService
{

    public function __construct(private ClientRepository $clientRepository = new ClientRepository())
    {
    }

    public function getClient($id): Client
    {
        return $this->clientRepository->findById($id);
    }

    public function getClients(?array $data): array
    {
        $search = $data['search'] ?? null;
        return $this->clientRepository->findClientsWithSearch($search);
    }

    public function updateClient(int $clientId, array $data): Client
    {
        $client = $this->clientRepository->findById($clientId);

        if (!$client) {
            throw new \RuntimeException("Client not found.");
        }

        $allowedFields = ['nom', 'email', 'phone'];
        $updateData = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            throw new \InvalidArgumentException("No valid fields provided for update.");
        }

        $this->clientRepository->update($updateData, ['id' => $clientId]);

        return $this->clientRepository->findById($clientId);
    }

    public function createClient($nom, $email, $phone): Client {
        $client = new Client(null, $nom, $email, $phone);
        if($this->clientRepository->save([
            'nom' => $client->getNom(), 
            'email' => $client->getEmail(),
            'phone' => $client->getPhone()
            ]))
            return $client;
        throw new ErrorException("We cant do this now");
    }

    public function deleteClient(int $clientId): Client
    {
        $client = $this->clientRepository->findById($clientId);
        if ($this->clientRepository->delete(['id' => $clientId])) {
            return $client;
        }
        throw new ErrorException("We cant do this now");
    }

} 