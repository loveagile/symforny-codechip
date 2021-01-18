<?php
namespace App\Service;

class UploadService
{
    private $folder;
    private $logger;

    public function __construct(string $uploadDir)
    {
        $this->folder = $uploadDir;
    }

    public function upload()
    {
        return $this->folder;
    }
}