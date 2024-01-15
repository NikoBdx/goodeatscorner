<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    public function __construct(private SluggerInterface $slugger) {}

    /**
     *
     * Upload un fichier et retourne le chemin vers ce dernier
     *
     * @param UploadedFile $path
     * @param string $directory
     * @return string le chemin du fichier uploadé
     */
    public function uploadFile(
        UploadedFile $path,
        string $directory
    ): string {

        $originalFilename = pathinfo($path->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$path->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $path->move(
                $directory,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $newFilename;

    }
}