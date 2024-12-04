<?php

namespace App\Helper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

    public function __construct(EntityManagerInterface $entityManager, string $directory)
    {
    }

    public function upload(UploadedFile $file, string $directory, string $name = "") : string
    {
        $fileName = ($name ? $name.'-': $name) . uniqid().$file->guessExtension();
        $file->move($directory, $fileName);
        return $fileName;
    }

    public function delete(string $directory, string $filename) : bool
    {
            return unlink($directory.'/'.$filename);
    }
}
