<?php
namespace Core\DataSources;
use Core\DataSource;
class MySQLDataSource implements DataSource
{
    private $host;
    private $dbname;
    private $username;
    private $password;

    public function __construct($host, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function getDsn(): string
    {
        return "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
