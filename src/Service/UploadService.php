<?php
namespace App\Service;

class UploadService
{
    private $folder;

    public function __construct(string $uploadDir)
    {
        $this->folder = $uploadDir;
    }

    public function upload($files, $targetFolder)
    {
        if (is_array($files)) {

            foreach ($files as $file) {
                $this->move($file, $targetFolder);
            }

        } else {
            $this->move($files, $targetFolder);
        }

    }

    private function move($file, $targetFolder)
    {
        $file->move(
            $this->folder . '/' . $targetFolder,
            $this->makeNewName($file));
    }

    private function makeNewName($file)
    {
        return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
    }
}