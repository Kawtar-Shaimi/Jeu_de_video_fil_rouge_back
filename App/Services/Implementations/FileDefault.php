<?php
namespace App\Services\Implementations;
use App\Services\Interfaces\FileService;

class FileDefault implements FileService{

    public function getFile($path){
        $baseDir = realpath(__DIR__ . '/../../../storage');
        $safePath = realpath($baseDir . '/' . $path);
        if (!$safePath || strpos($safePath, $baseDir) !== 0) {
            http_response_code(400);
            echo "Chemin non valide.";
            return;
        }

        if (!file_exists($safePath)) {
            http_response_code(404);
            echo "Fichier non trouvé.";
            return;
        }

        if (!is_readable($safePath)) {
            http_response_code(403);
            echo "Fichier non accessible.";
            return;
        }

        $fileName = basename($safePath);
        $size = filesize($safePath);
        $mimeType = mime_content_type($safePath) ?: 'application/octet-stream';

        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . $size);
        header('Content-Disposition: inline; filename="' . $fileName . '"');

        readfile($safePath);
        exit;
    }

    public function upload($dir, $file, $uniqueName){
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'Upload error code: ' . $file['error']]);
            return;
        }
        $uploadDir = realpath(__DIR__ . '/../../../storage' . $dir);
        if (!$uploadDir) {
            http_response_code(500);
            echo json_encode(['error' => 'Upload directory does not exist.']);
            return;
        }
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
                
        
        $targetFile = $uploadDir . '/' . $uniqueName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
           return true;
        } else {
            throw new \Error('Failed to move uploaded file.');
        }
        
    }

}