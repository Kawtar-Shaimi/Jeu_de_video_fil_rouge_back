<?php
namespace App\Controllers;

use App\Services\Implementations\FileDefault;
use App\Services\Interfaces\FileService;
use Core\Controller;
use Core\Decorators\Description;
use Core\Decorators\Route;
use Core\Router\RouteMethod;

#[Route('/files/v1')]
class FileController extends Controller
{
    private FileService $fileService;
    
    public function __construct(){
        parent::__construct();
        $this->fileService = new FileDefault();
    }

    #[Description("Récupère un fichier à partir du chemin fourni en tant que paramètre 'path'.")]
    #[Route('/', method: RouteMethod::GET)]
    public function serveStorage(): void
    {
        $path = $this->request->param('path');
        $this->fileService->getFile($path);
    }
    
    #[Description("Permet de téléverser un fichier dans le dossier spécifié via le paramètre 'dir'. Le fichier doit être envoyé avec la clé 'file'.")]
    #[Route('/', method: RouteMethod::POST)]
    public function uploadFile()
    {
        if (!$this->request->hasFile('file')) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded with key "file".']);
            return;
        }

        $file = $this->request->file('file');
        $dir = $this->request->input('dir');
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid() . ($ext ? '.' . $ext : '');
        $this->fileService->upload($dir, $file, $uniqueName);
        
    }


}