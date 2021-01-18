<?php
namespace App\Service;

class UploadService
{
    private $folder;

    public function __construct($uploadDir)
    {
        $this->folder = $uploadDir;
    }


    public function upload()
    {
        return 'Realizando upload';
    }
}