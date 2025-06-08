<?php

namespace Core;

class Request
{
    private string $method;
    private array $body;
    private array $headers;
    private array $files;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->files = $_FILES;

        $input = file_get_contents('php://input');
        $jsonBody = json_decode($input, true);

        $this->body = is_array($jsonBody) ? $jsonBody : $_POST;
    }


    public function input(string $key, $default = null)
    {
        return $key ? ($this->body[$key] ?? $default) : $this->body;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function all(): array
    {
        return array_merge($this->body, $this->files);
    }

    public function param(?string $key = null): mixed
    {
        if ($key === null) {
            return filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
        }
    
        return filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }    

    public function getMethod()
    {
        return $this->method;
    }

    public function relativeUrl(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $relativeUri = str_replace($scriptName, '', $requestUri);
        $relativeUri = strtok($relativeUri, '?');
        return $relativeUri;
    }
}
