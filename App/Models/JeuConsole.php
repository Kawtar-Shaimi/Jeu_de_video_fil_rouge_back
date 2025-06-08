<?php
namespace App\Models;

class JeuConsole extends Jeu
{
    private $plateforme;
    private $regionCode;

    public function __construct($id, $titre, $prix, $genre, $editeur, $stockDisponible, $plateforme, $regionCode)
    {
        parent::__construct($id, $titre, $prix, $genre, $editeur, $stockDisponible, 'Console');
        $this->plateforme = $plateforme;
        $this->regionCode = $regionCode;
    }

    public function jsonSerialize(): array
    {
        $baseData = parent::jsonSerialize();
        $baseData['plateforme'] = $this->plateforme;
        $baseData['regionCode'] = $this->regionCode;
        return $baseData;
    }

    public function getPlateforme()
    {
        return $this->plateforme;
    }

    public function setPlateforme($plateforme)
    {
        $this->plateforme = $plateforme;
    }

    public function getRegionCode()
    {
        return $this->regionCode;
    }

    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    }
} 