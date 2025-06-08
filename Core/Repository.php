<?php
namespace Core;

abstract class Repository
{
    protected Database $db;
    protected string $tableName;
    public function __construct($tableName)
    {
        $this->db = Database::getInstance();
        $this->tableName = $tableName;
    }

    public function get(array $data, string $key)
    {
        return $data[$key];
    }
    public function arrayMapper(array $data): array
    {
        return array_map([$this, 'mapper'], $data);
    }
    abstract protected function mapper(array $data): object;


}