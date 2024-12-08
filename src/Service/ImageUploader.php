<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mime\MimeTypes;

class ImageUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    private LoggerInterface $logger;

    public function __construct(string $targetDirectory, SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * Upload an image to a specific subdirectory.
     *
     * @param UploadedFile $file The file to upload.
     * @param string|null $subDirectory The subdirectory where the file will be saved.
     * @return string The new filename.
     * @throws \Exception If the file is invalid or cannot be uploaded.
     */
    public function upload(UploadedFile $file, ?string $subDirectory = null): string
    {
        $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        $this->logger->info('Début du processus de téléchargement de l\'image.');

        // Vérification du type MIME
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $this->logger->error('Type MIME invalide : ' . $mimeType);
            throw new \Exception('Le fichier doit être une image au format JPEG ou PNG.');
        }

        // Vérification de la taille du fichier
        if ($file->getSize() > $maxFileSize) {
            $this->logger->error('Fichier trop volumineux : ' . $file->getSize());
            throw new \Exception('La taille du fichier ne doit pas dépasser 5 Mo.');
        }

        // Génération d'un nom de fichier unique et sécurisé
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = (new MimeTypes())->getExtensions($mimeType)[0]; // Obtenir l'extension depuis le type MIME
        $newFilename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $extension);

        $this->logger->info('Nom de fichier généré : ' . $newFilename);

        // Détermination du répertoire cible
        $targetDirectory = $this->getTargetDirectory($subDirectory);

        // Création du sous-dossier si nécessaire
        if (!is_dir($targetDirectory)) {
            $this->logger->info('Création du répertoire : ' . $targetDirectory);
            if (!mkdir($targetDirectory, 0777, true) && !is_dir($targetDirectory)) {
                $this->logger->error('Impossible de créer le sous-dossier : ' . $targetDirectory);
                throw new \Exception('Impossible de créer le sous-dossier : ' . $targetDirectory);
            }
        }

        // Déplacement du fichier vers le répertoire cible
        try {
            $file->move($targetDirectory, $newFilename);
            $this->logger->info('Fichier déplacé vers : ' . $targetDirectory . '/' . $newFilename);
        } catch (FileException $e) {
            $this->logger->error('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
            throw new \Exception('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
        }

        $this->logger->info('Téléchargement de l\'image terminé avec succès.');

        return ($subDirectory ? $subDirectory . '/' : '') . $newFilename;
    }

    /**
     * Get the full target directory path.
     *
     * @param string|null $subDirectory The optional subdirectory.
     * @return string The full path to the target directory.
     */
    private function getTargetDirectory(?string $subDirectory = null): string
    {
        return $subDirectory 
            ? $this->targetDirectory . '/' . trim($subDirectory, '/')
            : $this->targetDirectory;
    }
}
