<?php
namespace App\Models;

class JeuPC extends Jeu
{
    private $configurationMinimale;
    private $supportDVD;

    public function __construct($id, $titre, $prix, $genre, $editeur, $stockDisponible, $configurationMinimale, $supportDVD)
    {
        parent::__construct($id, $titre, $prix, $genre, $editeur, $stockDisponible, 'PC');
        $this->configurationMinimale = $configurationMinimale;
        $this->supportDVD = $supportDVD;
    }

    public function jsonSerialize(): array
    {
        $baseData = parent::jsonSerialize();
        $baseData['configurationMinimale'] = $this->configurationMinimale;
        $baseData['supportDVD'] = $this->supportDVD;
        return $baseData;
    }

    public function getConfigurationMinimale()
    {
        return $this->configurationMinimale;
    }

    public function setConfigurationMinimale($configurationMinimale)
    {
        $this->configurationMinimale = $configurationMinimale;
    }

    public function getSupportDVD()
    {
        return $this->supportDVD;
    }

    public function setSupportDVD($supportDVD)
    {
        $this->supportDVD = $supportDVD;
    }
} 