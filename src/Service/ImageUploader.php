<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
    private $targetDirectory;
    private $slugger;

    // Constructeur pour initialiser le répertoire cible et le slugger
    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    // Méthode pour télécharger un fichier image
    public function upload(UploadedFile $file): string
    {
        // Extensions de fichier autorisées
        $allowedExtensions = ['jpeg', 'png'];
        $fileExtension = $file->guessExtension();
        if ($fileExtension==='jpg') {
            $fileExtension = 'jpeg';
        }

        // Vérifie si l'extension du fichier est autorisée
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new \Exception('Le fichier doit être une image (JPEG, PNG).');
        }

        // Génère un nom de fichier sécurisé qui ne contient pas de caractères problématiques (comme les accents, les espaces, etc.)
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileExtension;

        try {
            // Déplace le fichier vers le répertoire cible
            $file->move($this->getTargetDirectory(), $newFilename);
        } catch (FileException $e) {
            // Gère les erreurs de téléchargement de fichier
            throw new \Exception('Erreur lors du téléchargement du fichier');
        }

        // Retourne le nouveau nom de fichier
        return $newFilename;
    }

    // Méthode pour obtenir le répertoire cible
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
