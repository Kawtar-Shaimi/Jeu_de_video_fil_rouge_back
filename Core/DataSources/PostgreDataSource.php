<?php

namespace Core\DataSources;
use Core\DataSource;

class PostgreDataSource implements DataSource
{
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public function __construct($host, $port, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function getDsn(): string
    {
        return "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
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
