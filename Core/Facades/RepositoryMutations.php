<?php
namespace Core\Facades;

use Core\Repository;


abstract class RepositoryMutations extends Repository
{
    public function save(array $data): int
    {
        $pdo = $this->db->getPdo();
        $fields = array_keys($data);
        $columns = implode(', ', $fields);
        $placeholders = implode(', ', array_map(fn($key) => ":$key", $fields));
        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return (int) $pdo->lastInsertId();
    }

    public function update(array $data, array $clauses): bool
    {
        if (empty($data)) {
            throw new \InvalidArgumentException("Update data cannot be empty.");
        }

        $pdo = $this->db->getPdo();

        $setParts = array_map(fn($key) => "$key = :$key", array_keys($data));
        $setClause = implode(', ', $setParts);

        $whereParts = array_map(fn($key) => "$key = :where_$key", array_keys($clauses));
        $whereClause = implode(' AND ', $whereParts);

        $sql = "UPDATE {$this->tableName} SET $setClause WHERE $whereClause";
        $stmt = $pdo->prepare($sql);

        $params = $data;
        foreach ($clauses as $key => $value) {
            $params["where_$key"] = $value;
        }

        return $stmt->execute($params);
    }


    public function delete(array $clauses): bool
    {
        $pdo = $this->db->getPdo();
        $whereParts = array_map(fn($key) => "$key = :$key", array_keys($clauses));
        $whereClause = implode(' AND ', $whereParts);
        $sql = "DELETE FROM $this->tableName WHERE $whereClause";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($clauses);
    }

}