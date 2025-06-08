<?php
namespace App\Services\Implementations;
use App\Repositories\JeuRepository;
use App\Services\Interfaces\JeuService;
use App\Models\Jeu;
use ErrorException;

class JeuDefault implements JeuService
{

    public function __construct(private JeuRepository $jeuRepository = new JeuRepository())
    {
    }

    public function getJeu($id): Jeu
    {
        return $this->jeuRepository->findById($id);
    }

    public function getJeux(?array $data): array
    {
        $search = $data['search'] ?? null;
        return $this->jeuRepository->findJeuxWithSearch($search);
    }

    public function createJeu(array $data): Jeu
    {
        // Validate required fields
        $requiredFields = ['titre', 'prix', 'genre', 'editeur', 'stockDisponible', 'type'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new ErrorException("Field '$field' is required");
            }
        }

        // Prepare data for database insertion
        $jeuData = [
            'titre' => $data['titre'],
            'prix' => (float) $data['prix'],
            'genre' => $data['genre'],
            'editeur' => $data['editeur'],
            'stockDisponible' => (int) $data['stockDisponible'],
            'type' => $data['type']
        ];

        // Add type-specific fields with validation
        if ($data['type'] === 'PC') {
            // Validate PC-specific required fields
            if (empty($data['configurationMinimale'])) {
                throw new ErrorException("Configuration minimale is required for PC games");
            }
            
            $jeuData['configurationMinimale'] = $data['configurationMinimale'];
            $jeuData['supportDVD'] = isset($data['supportDVD']) ? (int) $data['supportDVD'] : 0;
            $jeuData['plateforme'] = null;
            $jeuData['regionCode'] = null;
        } elseif ($data['type'] === 'Console') {
            // Validate Console-specific required fields
            if (empty($data['plateforme'])) {
                throw new ErrorException("Plateforme is required for Console games");
            }
            if (empty($data['regionCode'])) {
                throw new ErrorException("Region code is required for Console games");
            }
            
            $jeuData['plateforme'] = $data['plateforme'];
            $jeuData['regionCode'] = $data['regionCode'];
            $jeuData['configurationMinimale'] = null;
            $jeuData['supportDVD'] = null;
        } else {
            throw new ErrorException("Type must be either 'PC' or 'Console'");
        }

        // Save to database
        $jeuId = $this->jeuRepository->save($jeuData);
        
        // Return the created game
        return $this->jeuRepository->findById($jeuId);
    }

    public function updateJeu(int $jeuId, array $data): Jeu
    {
        $jeu = $this->jeuRepository->findById($jeuId);

        if (!$jeu) {
            throw new \RuntimeException("Jeu not found.");
        }

        $allowedFields = ['titre', 'prix', 'genre', 'editeur', 'stockDisponible'];
        $updateData = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            throw new \InvalidArgumentException("No valid fields provided for update.");
        }

        $this->jeuRepository->update($updateData, ['id' => $jeuId]);

        return $this->jeuRepository->findById($jeuId);
    }

    public function deleteJeu(int $jeuId): Jeu
    {
        $jeu = $this->jeuRepository->findById($jeuId);
        if ($this->jeuRepository->delete(['id' => $jeuId])) {
            return $jeu;
        }
        throw new ErrorException("We cant do this now");
    }

} 