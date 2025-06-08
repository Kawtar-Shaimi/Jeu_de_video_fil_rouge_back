<?php
namespace App\Services\Interfaces;

interface FileService{

    public function getFile($path);
    public function upload(string $dir, $file, $uniqueName);

}