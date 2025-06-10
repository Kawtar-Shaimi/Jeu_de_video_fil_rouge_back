<?php

namespace Core\DataSources;

use Core\DataSource;

class MockDataSource implements DataSource
{
    public function getDsn(): string
    {
        // Utiliser SQLite en mémoire
        return 'sqlite::memory:';
    }

    public function getUsername(): string
    {
        return '';
    }

    public function getPassword(): string
    {
        return '';
    }
} 