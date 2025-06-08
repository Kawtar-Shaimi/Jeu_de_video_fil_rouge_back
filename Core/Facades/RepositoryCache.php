<?php

namespace Core\Facades;

abstract class RepositoryCache
{
    const ENCRYPTION_KEY = '0123456789abcdef0123456789abcdef';
    const CIPHER = 'AES-256-CBC';
    protected string $cacheFile;

    public function __construct()
    {
        $cacheDir = __DIR__ . '/../../.cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $filename = str_replace('\\', '_', static::class) . '.cache';
        $this->cacheFile = $cacheDir . '/' . $filename;

        $this->load();
    }

    abstract protected function getData(): array;
    abstract protected function setData(array $data): void;

    protected function load(): void
    {
        if (file_exists($this->cacheFile)) {
            $payload = file_get_contents($this->cacheFile);
            $raw = base64_decode($payload);
    
            $ivLength = openssl_cipher_iv_length(self::CIPHER);
            $iv = substr($raw, 0, $ivLength);
            $encrypted = substr($raw, $ivLength);
    
            $json = openssl_decrypt($encrypted, self::CIPHER, self::ENCRYPTION_KEY, 0, $iv);
            $data = json_decode($json, true);
    
            if (is_array($data)) {
                $this->setData($data);
            }
        }
    }
    
    protected function commit(): void
    {
        $json = json_encode($this->getData());
        
        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($json, self::CIPHER, self::ENCRYPTION_KEY, 0, $iv);

        $payload = base64_encode($iv . $encrypted);
        file_put_contents($this->cacheFile, $payload);
    }

    public function clear(): void
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }
}
