<?php
namespace Core;
use Core\DataSource;
use PDO;
use PDOException;
use Exception;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct(DataSource $dataSource)
    {
        try {
            $this->pdo = new PDO(
                $dataSource->getDsn(),
                $dataSource->getUsername(),
                $dataSource->getPassword(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database Connection Failed', 'message' => $e->getMessage()]));
        }
    }

    public static function init(DataSource $dataSource)
    {
        if (self::$instance === null) {
            self::$instance = new Database($dataSource);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            throw new Exception("Database has not been initialized. Call Database::init() first.");
        }

        return self::$instance;
    }

    public function getPdo(){
        return $this->pdo;
    }
}