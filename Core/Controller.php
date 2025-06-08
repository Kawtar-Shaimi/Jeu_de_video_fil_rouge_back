<?php
namespace Core;

class Controller
{
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function json($data, $status = 200)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            header("Access-Control-Allow-Methods: *");
            header("HTTP/1.1 200 OK");
            exit();
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}
