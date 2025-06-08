<?php

namespace App\Repositories;
use App\Models\Jeu;
use App\Models\JeuPC;
use App\Models\JeuConsole;
use Core\Facades\RepositoryMutations;
use PDO;

class JeuRepository extends RepositoryMutations
{

    public function __construct()
    {
        parent::__construct('jeux');
    }

    function findAllJeux(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName;")->fetchAll((PDO::FETCH_ASSOC));
        return $this->arrayMapper($data);
    }

    public function findJeuxWithSearch(?string $search = null): array
    {
        if (empty($search)) {
            return $this->findAllJeux();
        }
        
        $sql = "SELECT * FROM $this->tableName WHERE titre LIKE :search OR genre LIKE :search OR editeur LIKE :search";
        $stmt = $this->db->getPdo()->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->execute(['search' => $searchParam]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->arrayMapper($data);
    }

    public function findById($id): Jeu
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Jeu with ID $id not found.");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Jeu
    {
        $id = $this->get($data, 'id');
        $titre = $this->get($data, 'titre');
        $prix = $this->get($data, 'prix');
        $genre = $this->get($data, 'genre');
        $editeur = $this->get($data, 'editeur');
        $stockDisponible = $this->get($data, 'stockDisponible');
        $typeJeu = $this->get($data, 'type');

        if ($typeJeu === 'PC') {
            $configurationMinimale = $this->get($data, 'configurationMinimale');
            $supportDVD = (bool) $this->get($data, 'supportDVD');
            return new JeuPC($id, $titre, $prix, $genre, $editeur, $stockDisponible, $configurationMinimale, $supportDVD);
        } else {
            $plateforme = $this->get($data, 'plateforme');
            $regionCode = $this->get($data, 'regionCode');
            return new JeuConsole($id, $titre, $prix, $genre, $editeur, $stockDisponible, $plateforme, $regionCode);
        }
    }

} 