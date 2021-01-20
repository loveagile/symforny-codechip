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
            $newFiles = [];
            foreach ($files as $file) {
                $newFiles[] = $this->move($file, $targetFolder);
            }

            return $newFiles;

        } else {
            return $this->move($files, $targetFolder);
        }

    }

    private function move($file, $targetFolder)
    {
        $newFileName = $this->makeNewName($file);
        $file->move(
            $this->folder . '/' . $targetFolder,
            $newFileName
        );

        return $newFileName;
    }

    private function makeNewName($file)
    {
        return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
    }
}