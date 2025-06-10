<?php

namespace Core\DataSources;

use Core\DataSource;

class ArrayDataSource implements DataSource
{
    public function getDsn(): string
    {
        // Utiliser une chaîne spéciale pour identifier cette source de données
        return 'array:inmemory';
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