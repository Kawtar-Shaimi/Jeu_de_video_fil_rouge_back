<?php
namespace App\Services\Interfaces;
use App\Models\Jeu;

interface JeuService
{
    public function getJeu(int $id): Jeu;

    public function getJeux(?array $filters): array;

    public function createJeu(array $data): Jeu;

    public function updateJeu(int $jeuId, array $data): Jeu;

    public function deleteJeu(int $jeuId): Jeu;
} 