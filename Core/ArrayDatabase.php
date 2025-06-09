<?php

namespace Core;

class ArrayDatabase
{
    private static $tables = [];
    private static $nextIds = [];

    public function __construct()
    {
        // Initialiser les données de test
        $this->initializeTestData();
    }

    public function exec($sql)
    {
        // Simuler les opérations SQL de base
        if (preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/i', $sql, $matches)) {
            $table = $matches[1];
            if (!isset(self::$tables[$table])) {
                self::$tables[$table] = [];
                self::$nextIds[$table] = 1;
            }
            return true;
        }

        if (preg_match('/INSERT INTO (\w+).*VALUES/i', $sql, $matches)) {
            $table = $matches[1];
            // Pour simplifier, on va traiter seulement les insertions prédéfinies
            return $this->handleInsert($table, $sql);
        }

        return true;
    }

    public function query($sql)
    {
        if (preg_match('/SELECT \* FROM (\w+)/i', $sql, $matches)) {
            $table = $matches[1];
            return new ArrayStatement(self::$tables[$table] ?? []);
        }
        return new ArrayStatement([]);
    }

    public function prepare($sql)
    {
        return new ArrayPreparedStatement($sql, self::$tables);
    }

    private function initializeTestData()
    {
        // Employés
        self::$tables['employees'] = [
            [
                'id' => 1,
                'name' => 'Ahmed Mansour',
                'email' => 'ahmed.mansour@example.com',
                'photo' => '/employees_images/ahmed.jpeg',
                'salary' => 4500.00,
                'password' => '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'
            ],
            [
                'id' => 2,
                'name' => 'Fatima Zahra',
                'email' => 'fatima.zahra@example.com',
                'photo' => '/employees_images/fatima.jpg',
                'salary' => 5200.50,
                'password' => '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'
            ]
        ];

        // Clients
        self::$tables['clients'] = [
            ['id' => 1, 'nom' => 'Jean Dupont', 'email' => 'jean.dupont@email.com', 'phone' => '0123456789'],
            ['id' => 2, 'nom' => 'Marie Martin', 'email' => 'marie.martin@email.com', 'phone' => '0987654321'],
            ['id' => 3, 'nom' => 'Pierre Durand', 'email' => 'pierre.durand@email.com', 'phone' => '0654321987'],
            ['id' => 4, 'nom' => 'Sophie Bernard', 'email' => 'sophie.bernard@email.com', 'phone' => '0321654987']
        ];

        // Jeux
        self::$tables['jeux'] = [
            [
                'id' => 1, 'titre' => 'Cyberpunk 2077', 'prix' => 59.99, 'genre' => 'RPG',
                'editeur' => 'CD Projekt Red', 'stockDisponible' => 15, 'type' => 'PC',
                'configurationMinimale' => 'Intel Core i5-3570K, 8GB RAM, GTX 780', 'supportDVD' => true,
                'plateforme' => null, 'regionCode' => null
            ],
            [
                'id' => 2, 'titre' => 'The Witcher 3', 'prix' => 39.99, 'genre' => 'RPG',
                'editeur' => 'CD Projekt Red', 'stockDisponible' => 25, 'type' => 'PC',
                'configurationMinimale' => 'Intel Core i5-2500K, 6GB RAM, GTX 660', 'supportDVD' => true,
                'plateforme' => null, 'regionCode' => null
            ],
            [
                'id' => 3, 'titre' => 'Half-Life: Alyx', 'prix' => 49.99, 'genre' => 'Action',
                'editeur' => 'Valve', 'stockDisponible' => 8, 'type' => 'PC',
                'configurationMinimale' => 'Intel Core i5-7500, 12GB RAM, GTX 1060', 'supportDVD' => false,
                'plateforme' => null, 'regionCode' => null
            ],
            [
                'id' => 4, 'titre' => 'The Last of Us Part II', 'prix' => 49.99, 'genre' => 'Action',
                'editeur' => 'Naughty Dog', 'stockDisponible' => 20, 'type' => 'Console',
                'configurationMinimale' => null, 'supportDVD' => null,
                'plateforme' => 'PlayStation 4', 'regionCode' => 'PAL'
            ],
            [
                'id' => 5, 'titre' => 'Halo Infinite', 'prix' => 59.99, 'genre' => 'FPS',
                'editeur' => 'Microsoft', 'stockDisponible' => 18, 'type' => 'Console',
                'configurationMinimale' => null, 'supportDVD' => null,
                'plateforme' => 'Xbox Series X', 'regionCode' => 'NTSC'
            ],
            [
                'id' => 6, 'titre' => 'Super Mario Odyssey', 'prix' => 54.99, 'genre' => 'Plateforme',
                'editeur' => 'Nintendo', 'stockDisponible' => 12, 'type' => 'Console',
                'configurationMinimale' => null, 'supportDVD' => null,
                'plateforme' => 'Nintendo Switch', 'regionCode' => 'Multi'
            ],
            [
                'id' => 7, 'titre' => 'Ghost of Tsushima', 'prix' => 44.99, 'genre' => 'Action',
                'editeur' => 'Sucker Punch', 'stockDisponible' => 16, 'type' => 'Console',
                'configurationMinimale' => null, 'supportDVD' => null,
                'plateforme' => 'PlayStation 5', 'regionCode' => 'PAL'
            ]
        ];

        // Ventes
        self::$tables['ventes'] = [
            ['id' => 1, 'dateVente' => '2024-01-15 10:30:00', 'quantite' => 1, 'montantTotal' => 71.99, 'statut' => 'PAYÉE', 'clientId' => 1, 'jeuId' => 1],
            ['id' => 2, 'dateVente' => '2024-01-16 14:20:00', 'quantite' => 2, 'montantTotal' => 95.98, 'statut' => 'PAYÉE', 'clientId' => 2, 'jeuId' => 2],
            ['id' => 3, 'dateVente' => '2024-01-17 16:45:00', 'quantite' => 1, 'montantTotal' => 59.99, 'statut' => 'EN_ATTENTE', 'clientId' => 3, 'jeuId' => 4],
            ['id' => 4, 'dateVente' => '2024-01-18 11:15:00', 'quantite' => 1, 'montantTotal' => 65.99, 'statut' => 'PAYÉE', 'clientId' => 4, 'jeuId' => 6]
        ];

        self::$nextIds = [
            'employees' => 3,
            'clients' => 5,
            'jeux' => 8,
            'ventes' => 5
        ];
    }

    private function handleInsert($table, $sql)
    {
        // Pour simplifier, on ne traite pas les insertions dynamiques dans ce mock
        return true;
    }

    public static function getTables()
    {
        return self::$tables;
    }

    public static function getTable($name)
    {
        return self::$tables[$name] ?? [];
    }

    public static function addToTable($table, $data)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = [];
            self::$nextIds[$table] = 1;
        }

        $data['id'] = self::$nextIds[$table]++;
        self::$tables[$table][] = $data;
        return $data['id'];
    }

    public static function updateInTable($table, $id, $data)
    {
        foreach (self::$tables[$table] as &$row) {
            if ($row['id'] == $id) {
                $row = array_merge($row, $data);
                return true;
            }
        }
        return false;
    }

    public static function deleteFromTable($table, $id)
    {
        foreach (self::$tables[$table] as $key => $row) {
            if ($row['id'] == $id) {
                unset(self::$tables[$table][$key]);
                self::$tables[$table] = array_values(self::$tables[$table]);
                return true;
            }
        }
        return false;
    }

    public function lastInsertId()
    {
        // Pour ArrayDatabase, on peut retourner le dernier ID utilisé
        // Dans un contexte réel, ce serait l'ID du dernier enregistrement inséré
        return max(array_values(self::$nextIds)) - 1;
    }
}

class ArrayStatement
{
    private $data;

    public function __construct($data)
    {
        $this->data = array_values($data);
    }

    public function fetchAll($mode = null)
    {
        return $this->data;
    }

    public function fetch($mode = null)
    {
        return array_shift($this->data);
    }
}

class ArrayPreparedStatement
{
    private $sql;
    private $tables;

    public function __construct($sql, &$tables)
    {
        $this->sql = $sql;
        $this->tables = &$tables;
    }

    public function execute($params = [])
    {
        // Simplifier : traiter seulement SELECT avec WHERE id = :id
        if (preg_match('/SELECT \* FROM (\w+) WHERE id = :id/i', $this->sql, $matches)) {
            $table = $matches[1];
            $id = $params['id'];
            
            foreach (($this->tables[$table] ?? []) as $row) {
                if ($row['id'] == $id) {
                    $this->result = $row;
                    return true;
                }
            }
            $this->result = null;
        }
        return true;
    }

    public function fetch($mode = null)
    {
        return $this->result ?? false;
    }
} 